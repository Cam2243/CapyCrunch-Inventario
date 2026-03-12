<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class InventarioController extends Controller
{
    // ─────────────────────────────────────────
    // CATÁLOGO BASE de productos Capycrunch
    // Se puede ampliar desde la UI
    // ─────────────────────────────────────────
    private function catalogoBase(): array
    {
        return [
            ['id' => 'choco',    'nombre' => 'Galleta de Chocolate',      'emoji' => '🍫', 'precio' => 3500],
            ['id' => 'vainilla', 'nombre' => 'Galleta de Vainilla',       'emoji' => '🍪', 'precio' => 3000],
            ['id' => 'canela',   'nombre' => 'Galleta de Canela',         'emoji' => '🌰', 'precio' => 3200],
            ['id' => 'limon',    'nombre' => 'Galleta de Limón',          'emoji' => '🍋', 'precio' => 3200],
            ['id' => 'avena',    'nombre' => 'Galleta de Avena y Pasas',  'emoji' => '🌾', 'precio' => 3000],
        ];
    }

    // ─────────────────────────────────────────
    // INDEX — pantalla principal
    // ─────────────────────────────────────────
    public function index()
    {
        // Si no hay catálogo en sesión, cargar el base
        if (!session()->has('catalogo')) {
            session(['catalogo' => $this->catalogoBase()]);
        }

        $catalogo    = session('catalogo', []);
        $apertura    = session('apertura', []);      // ['id' => cantidad_inicial]
        $inventario  = session('inventario', []);    // ['id' => stock_actual]
        $ventas      = session('ventas', []);        // ['id' => unidades_vendidas]
        $historial   = session('historial', []);     // array de transacciones
        $diaAbierto  = session('dia_abierto', false);
        $fecha       = session('fecha_apertura', Carbon::now()->format('d/m/Y'));

        return view('inventario.index', compact(
            'catalogo', 'apertura', 'inventario',
            'ventas', 'historial', 'diaAbierto', 'fecha'
        ));
    }

    // ─────────────────────────────────────────
    // APERTURA DEL DÍA
    // ─────────────────────────────────────────
    public function apertura(Request $request)
    {
        $catalogo = session('catalogo', []);
        $stockInicial = [];

        foreach ($catalogo as $producto) {
            $cantidad = (int) $request->input('stock_' . $producto['id'], 0);
            $stockInicial[$producto['id']] = $cantidad;
        }

        session([
            'apertura'       => $stockInicial,
            'inventario'     => $stockInicial,
            'ventas'         => array_fill_keys(array_column($catalogo, 'id'), 0),
            'historial'      => [],
            'dia_abierto'    => true,
            'fecha_apertura' => Carbon::now()->format('d/m/Y'),
            'hora_apertura'  => Carbon::now()->format('H:i'),
        ]);

        return redirect()->route('inicio')->with('success', '¡Día abierto! Stock registrado correctamente.');
    }

    // ─────────────────────────────────────────
    // AGREGAR NUEVO PRODUCTO AL CATÁLOGO
    // ─────────────────────────────────────────
    public function agregarProducto(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:60',
            'precio' => 'required|numeric|min:1',
            'emoji'  => 'nullable|string|max:4',
        ]);

        $catalogo = session('catalogo', []);

        // Generar ID único
        $id = 'prod_' . uniqid();

        $catalogo[] = [
            'id'     => $id,
            'nombre' => $request->nombre,
            'emoji'  => $request->emoji ?: '🍪',
            'precio' => (float) $request->precio,
        ];

        session(['catalogo' => $catalogo]);

        // Si el día ya está abierto, inicializar el nuevo producto
        if (session('dia_abierto')) {
            $inventario = session('inventario', []);
            $ventas     = session('ventas', []);
            $apertura   = session('apertura', []);

            $inventario[$id] = 0;
            $ventas[$id]     = 0;
            $apertura[$id]   = 0;

            session(compact('inventario', 'ventas', 'apertura'));
        }

        return redirect()->route('inicio')->with('success', 'Producto "' . $request->nombre . '" agregado al catálogo.');
    }

    // ─────────────────────────────────────────
    // REGISTRAR VENTA EN TIEMPO REAL
    // ─────────────────────────────────────────
    public function registrarVenta(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|string',
            'cantidad'    => 'required|integer|min:1',
        ]);

        $id       = $request->producto_id;
        $cantidad = (int) $request->cantidad;

        $inventario = session('inventario', []);
        $ventas     = session('ventas', []);
        $historial  = session('historial', []);
        $catalogo   = session('catalogo', []);

        // Buscar el producto
        $producto = collect($catalogo)->firstWhere('id', $id);

        if (!$producto) {
            return redirect()->route('inicio')->with('error', 'Producto no encontrado.');
        }

        $stockActual = $inventario[$id] ?? 0;

        if ($cantidad > $stockActual) {
            return redirect()->route('inicio')->with('error', "No hay suficiente stock de {$producto['nombre']}. Stock actual: {$stockActual}");
        }

        // Descontar inventario
        $inventario[$id] = $stockActual - $cantidad;
        $ventas[$id]     = ($ventas[$id] ?? 0) + $cantidad;

        // Guardar en historial
        $historial[] = [
            'hora'     => Carbon::now()->format('H:i:s'),
            'producto' => $producto['nombre'],
            'emoji'    => $producto['emoji'],
            'cantidad' => $cantidad,
            'subtotal' => $cantidad * $producto['precio'],
        ];

        session(compact('inventario', 'ventas', 'historial'));

        return redirect()->route('inicio')->with('success', "✓ Venta registrada: {$cantidad} × {$producto['nombre']}");
    }

    // ─────────────────────────────────────────
    // CIERRE DE CAJA
    // ─────────────────────────────────────────
    public function cierre()
    {
        $catalogo   = session('catalogo', []);
        $apertura   = session('apertura', []);
        $inventario = session('inventario', []);
        $ventas     = session('ventas', []);
        $historial  = session('historial', []);
        $fecha      = session('fecha_apertura', Carbon::now()->format('d/m/Y'));
        $horaAp     = session('hora_apertura', '--:--');

        // Construir resumen por producto
        $resumen = [];
        $totalDinero = 0;

        foreach ($catalogo as $producto) {
            $id            = $producto['id'];
            $vendidas      = $ventas[$id] ?? 0;
            $sobrante      = $inventario[$id] ?? 0;
            $inicio        = $apertura[$id] ?? 0;
            $subtotal      = $vendidas * $producto['precio'];
            $totalDinero  += $subtotal;

            $resumen[] = [
                'nombre'   => $producto['nombre'],
                'emoji'    => $producto['emoji'],
                'precio'   => $producto['precio'],
                'inicio'   => $inicio,
                'vendidas' => $vendidas,
                'sobrante' => $sobrante,
                'subtotal' => $subtotal,
            ];
        }

        $horaCierre = Carbon::now()->format('H:i');

        return view('inventario.cierre', compact(
            'resumen', 'totalDinero', 'historial',
            'fecha', 'horaAp', 'horaCierre'
        ));
    }

    // ─────────────────────────────────────────
    // REINICIAR — nueva apertura
    // ─────────────────────────────────────────
    public function reiniciar()
    {
        // Conservar el catálogo pero limpiar el día
        $catalogo = session('catalogo');
        session()->flush();
        session(['catalogo' => $catalogo]);

        return redirect()->route('inicio')->with('success', 'Día reiniciado. Listo para una nueva apertura.');
    }
}