<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class InventarioController extends Controller
{
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

    public function index()
    {
        if (!session()->has('catalogo')) {
            session(['catalogo' => $this->catalogoBase()]);
        }

        $catalogo    = session('catalogo', []);
        $apertura    = session('apertura', []);      
        $inventario  = session('inventario', []);    
        $ventas      = session('ventas', []);        
        $historial   = session('historial', []);     
        $diaAbierto  = session('dia_abierto', false);
        $fecha       = session('fecha_apertura', Carbon::now()->format('d/m/Y'));

        return view('inventario.index', compact(
            'catalogo', 'apertura', 'inventario',
            'ventas', 'historial', 'diaAbierto', 'fecha'
        ));
    }

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

    public function agregarProducto(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:60',
            'precio' => 'required|numeric|min:1',
            'emoji'  => 'nullable|string|max:4',
        ]);

        $catalogo = session('catalogo', []);

        $id = 'prod_' . uniqid();

        $catalogo[] = [
            'id'     => $id,
            'nombre' => $request->nombre,
            'emoji'  => $request->emoji ?: '🍪',
            'precio' => (float) $request->precio,
        ];

        session(['catalogo' => $catalogo]);

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

        $producto = collect($catalogo)->firstWhere('id', $id);

        if (!$producto) {
            return redirect()->route('inicio')->with('error', 'Producto no encontrado.');
        }

        $stockActual = $inventario[$id] ?? 0;

        if ($cantidad > $stockActual) {
            return redirect()->route('inicio')->with('error', "No hay suficiente stock de {$producto['nombre']}. Stock actual: {$stockActual}");
        }

        $inventario[$id] = $stockActual - $cantidad;
        $ventas[$id]     = ($ventas[$id] ?? 0) + $cantidad;

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

    public function cierre()
    {
        $catalogo   = session('catalogo', []);
        $apertura   = session('apertura', []);
        $inventario = session('inventario', []);
        $ventas     = session('ventas', []);
        $historial  = session('historial', []);
        $fecha      = session('fecha_apertura', Carbon::now()->format('d/m/Y'));
        $horaAp     = session('hora_apertura', '--:--');

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

    public function reiniciar()
    {
        $catalogo = session('catalogo');
        session()->flush();
        session(['catalogo' => $catalogo]);

        return redirect()->route('inicio')->with('success', 'Día reiniciado. Listo para una nueva apertura.');
    }
    public function editarProducto(Request $request)
{
    $request->validate([
        'id'     => 'required|string',
        'nombre' => 'required|string|max:60',
        'precio' => 'required|numeric|min:1',
        'emoji'  => 'nullable|string|max:4',
    ]);

    $catalogo = session('catalogo', []);

    foreach ($catalogo as &$producto) {
        if ($producto['id'] === $request->id) {
            $producto['nombre'] = $request->nombre;
            $producto['precio'] = (float) $request->precio;
            $producto['emoji']  = $request->emoji ?: $producto['emoji'];
            break;
        }
    }

    session(['catalogo' => $catalogo]);

    return redirect()->route('inicio')->with('success', 'Producto actualizado correctamente.');
}

public function eliminarProducto(Request $request)
{
    $request->validate(['id' => 'required|string']);

    $id       = $request->id;
    $catalogo = session('catalogo', []);

    $catalogo = array_values(array_filter($catalogo, fn($p) => $p['id'] !== $id));

    session(['catalogo' => $catalogo]);

    if (session('dia_abierto')) {
        foreach (['inventario', 'ventas', 'apertura'] as $clave) {
            $arr = session($clave, []);
            unset($arr[$id]);
            session([$clave => $arr]);
        }
    }

    return redirect()->route('inicio')->with('success', 'Producto eliminado del catálogo.');
}
}