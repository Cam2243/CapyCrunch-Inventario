<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CapyCrunch — Cierre de Caja</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;0,9..144,700;1,9..144,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --cream:    #fdf6ec;
            --warm:     #f5e6cc;
            --caramel:  #c8813a;
            --caramel2: #e8a55a;
            --brown:    #4a2c0a;
            --brown2:   #6b3f12;
            --text:     #2d1a06;
            --text2:    #7a5230;
            --text3:    #b8906a;
            --green:    #2d6a4f;
            --green-lt: #52b788;
            --red:      #c1440e;
            --surface:  #fff8ee;
            --border:   #e8d5b5;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: var(--cream);
            color: var(--text);
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background-image: radial-gradient(ellipse 60% 40% at 50% 0%, rgba(200,129,58,0.1), transparent 60%);
        }

        .topbar {
            background: var(--brown);
            color: var(--warm);
            padding: 0.85rem 2rem;
            display: flex; justify-content: space-between; align-items: center;
        }

        .brand { display: flex; align-items: center; gap: 0.75rem; }
        .brand-icon { font-size: 1.6rem; }
        .brand-name {
            font-family: 'Fraunces', serif;
            font-size: 1.1rem; font-weight: 700; color: var(--caramel2);
        }

        .btn-back {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            color: var(--warm); border-radius: 8px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.8rem; font-weight: 600;
            padding: 0.45rem 1rem; cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
        }
        .btn-back:hover { background: rgba(255,255,255,0.15); }

        main {
            max-width: 820px; margin: 0 auto;
            padding: 2.5rem 1.5rem 4rem;
        }

        .cierre-header {
            text-align: center; margin-bottom: 2.5rem;
            animation: fadeUp 0.5s ease;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .cierre-icon { font-size: 3rem; margin-bottom: 0.75rem; display: block; }

        .cierre-header h1 {
            font-family: 'Fraunces', serif;
            font-size: 2rem; font-weight: 700; color: var(--brown);
            margin-bottom: 0.5rem;
        }

        .cierre-meta {
            font-size: 0.82rem; color: var(--text3);
            display: flex; justify-content: center; gap: 1.5rem; flex-wrap: wrap;
        }

        .cierre-meta span { display: flex; align-items: center; gap: 0.3rem; }

        .resumen-top {
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 1rem; margin-bottom: 2rem;
            animation: fadeUp 0.5s ease 0.1s both;
        }

        .r-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px; padding: 1.25rem;
            text-align: center;
        }

        .r-card.highlight {
            background: var(--brown);
            border-color: var(--brown);
        }

        .r-card .r-label {
            font-size: 0.68rem; font-weight: 600;
            letter-spacing: 0.1em; text-transform: uppercase;
            color: var(--text3); margin-bottom: 0.5rem;
        }

        .r-card.highlight .r-label { color: rgba(245,230,204,0.6); }

        .r-card .r-value {
            font-family: 'Fraunces', serif;
            font-size: 1.9rem; font-weight: 700; color: var(--brown); line-height: 1;
        }

        .r-card.highlight .r-value { color: var(--caramel2); }
        .r-card .r-sub { font-size: 0.72rem; color: var(--text3); margin-top: 0.3rem; }
        .r-card.highlight .r-sub { color: rgba(245,230,204,0.5); }

        .detalle-section {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px; overflow: hidden;
            margin-bottom: 1.5rem;
            animation: fadeUp 0.5s ease 0.2s both;
        }

        .detalle-header {
            background: var(--warm);
            padding: 0.9rem 1.5rem;
            font-size: 0.7rem; font-weight: 700;
            letter-spacing: 0.1em; text-transform: uppercase; color: var(--text2);
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr 1fr;
            gap: 0.5rem;
        }

        .detalle-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr 1fr;
            gap: 0.5rem;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border);
            align-items: center;
            transition: background 0.15s;
        }

        .detalle-row:last-child { border-bottom: none; }
        .detalle-row:hover { background: var(--warm); }

        .dr-producto { display: flex; align-items: center; gap: 0.5rem; }
        .dr-emoji { font-size: 1.2rem; }
        .dr-nombre { font-size: 0.85rem; font-weight: 600; color: var(--text); }

        .dr-num {
            font-size: 0.88rem; font-weight: 600; color: var(--text); text-align: center;
        }

        .dr-num.vendidas { color: var(--green); }
        .dr-num.sobrante { color: var(--text3); }

        .dr-total {
            font-size: 0.88rem; font-weight: 700; color: var(--brown); text-align: right;
        }

        .dr-total.zero { color: var(--text3); font-weight: 400; }

        /* Barra de progreso mini */
        .dr-barra-wrap {
            background: var(--warm);
            border-radius: 999px; height: 5px;
            overflow: hidden;
        }

        .dr-barra {
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(to right, var(--caramel), var(--caramel2));
        }

        .historial-section {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px; padding: 1.5rem;
            margin-bottom: 1.5rem;
            animation: fadeUp 0.5s ease 0.3s both;
        }

        .hist-title {
            font-family: 'Fraunces', serif;
            font-size: 1rem; font-weight: 600; color: var(--brown);
            margin-bottom: 1rem;
            padding-bottom: 0.75rem; border-bottom: 1px solid var(--border);
        }

        .hist-list { display: flex; flex-direction: column; gap: 0.4rem; max-height: 280px; overflow-y: auto; }
        .hist-list::-webkit-scrollbar { width: 4px; }
        .hist-list::-webkit-scrollbar-thumb { background: var(--border); border-radius: 2px; }

        .h-item {
            display: flex; align-items: center; gap: 0.6rem;
            background: var(--cream); border-radius: 8px;
            padding: 0.5rem 0.8rem; font-size: 0.78rem;
        }

        .h-hora { color: var(--text3); font-size: 0.68rem; min-width: 55px; }
        .h-desc { flex: 1; color: var(--text); font-weight: 500; }
        .h-monto { color: var(--green); font-weight: 700; }

        .hist-empty { text-align: center; color: var(--text3); font-size: 0.82rem; padding: 1rem; }

        .acciones {
            display: flex; gap: 1rem; justify-content: center;
            animation: fadeUp 0.5s ease 0.4s both;
        }

        .btn-nuevo-dia {
            background: var(--brown);
            color: var(--cream); border: none; border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.9rem; font-weight: 700;
            padding: 0.85rem 2rem; cursor: pointer;
            transition: background 0.2s, transform 0.1s;
        }

        .btn-nuevo-dia:hover { background: var(--brown2); }
        .btn-nuevo-dia:active { transform: scale(0.98); }

        @media (max-width: 640px) {
            .resumen-top { grid-template-columns: 1fr 1fr; }
            .detalle-header, .detalle-row {
                grid-template-columns: 2fr 1fr 1fr 1fr;
            }
            .hide-sm { display: none; }
            .acciones { flex-direction: column; }
        }

        @media print {
            .topbar .btn-back, .acciones { display: none; }
            body { background: white; }
        }

        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .bg-stone-900, .bg-stone-800 { background: white !important; color: black !important; }
            * { color: black !important; }
        }
    </style>
</head>
<body>

<div class="topbar">
    <div class="brand">
        <span class="brand-icon">🐾</span>
        <div>
            <div class="brand-name">CapyCrunch</div>
        </div>
    </div>
    <a href="{{ route('inicio') }}" class="btn-back">← Volver al inventario</a>
</div>

<main>

    <div class="cierre-header">
        <span class="cierre-icon">🧾</span>
        <h1>Cierre de Caja</h1>
        <div class="cierre-meta">
            <span>📅 {{ $fecha }}</span>
            <span>🌅 Apertura: {{ $horaAp }}</span>
            <span>🌙 Cierre: {{ $horaCierre }}</span>
        </div>
    </div>

    @php
        $totalVendidas  = array_sum(array_column($resumen, 'vendidas'));
        $totalSobrante  = array_sum(array_column($resumen, 'sobrante'));
        $totalInicio    = array_sum(array_column($resumen, 'inicio'));
        $pctVendido     = $totalInicio > 0 ? round(($totalVendidas / $totalInicio) * 100) : 0;
    @endphp

    <div class="resumen-top">
        <div class="r-card highlight">
            <div class="r-label">💰 Total recaudado</div>
            <div class="r-value">$ {{ number_format($totalDinero, 0, ',', '.') }}</div>
            <div class="r-sub">ingresos del día</div>
        </div>
        <div class="r-card">
            <div class="r-label">🍪 Galletas vendidas</div>
            <div class="r-value">{{ $totalVendidas }}</div>
            <div class="r-sub">de {{ $totalInicio }} iniciales ({{ $pctVendido }}%)</div>
        </div>
        <div class="r-card">
            <div class="r-label">📦 Stock sobrante</div>
            <div class="r-value">{{ $totalSobrante }}</div>
            <div class="r-sub">unidades sin vender</div>
        </div>
    </div>

    <div class="detalle-section">
        <div class="detalle-header">
            <div>Producto</div>
            <div style="text-align:center">Inicio</div>
            <div style="text-align:center">Vendidas</div>
            <div style="text-align:center">Sobrante</div>
            <div class="hide-sm" style="text-align:center">% vendido</div>
            <div style="text-align:right">Recaudado</div>
        </div>

        @foreach($resumen as $r)
        @php
            $pct = $r['inicio'] > 0 ? ($r['vendidas'] / $r['inicio']) * 100 : 0;
        @endphp
        <div class="detalle-row">
            <div class="dr-producto">
                <span class="dr-emoji">{{ $r['emoji'] }}</span>
                <div>
                    <div class="dr-nombre">{{ $r['nombre'] }}</div>
                    <div style="font-size:0.7rem;color:var(--text3)">$ {{ number_format($r['precio'], 0, ',', '.') }} c/u</div>
                </div>
            </div>
            <div class="dr-num">{{ $r['inicio'] }}</div>
            <div class="dr-num vendidas">{{ $r['vendidas'] }}</div>
            <div class="dr-num sobrante">{{ $r['sobrante'] }}</div>
            <div class="hide-sm">
                <div class="dr-barra-wrap">
                    <div class="dr-barra" style="width: {{ min(100, $pct) }}%"></div>
                </div>
                <div style="font-size:0.68rem;color:var(--text3);text-align:center;margin-top:0.2rem">{{ round($pct) }}%</div>
            </div>
            <div class="dr-total {{ $r['subtotal'] == 0 ? 'zero' : '' }}">
                $ {{ number_format($r['subtotal'], 0, ',', '.') }}
            </div>
        </div>
        @endforeach
    </div>

    @if(count($historial) > 0)
    <div class="historial-section">
        <div class="hist-title">📋 Historial de transacciones ({{ count($historial) }})</div>
        <div class="hist-list">
            @foreach(array_reverse($historial) as $h)
            <div class="h-item">
                <span class="h-hora">{{ $h['hora'] }}</span>
                <span>{{ $h['emoji'] }}</span>
                <span class="h-desc">{{ $h['cantidad'] }}× {{ $h['producto'] }}</span>
                <span class="h-monto">$ {{ number_format($h['subtotal'], 0, ',', '.') }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="historial-section">
        <div class="hist-empty">No se registraron ventas en este día.</div>
    </div>
    @endif

    <div class="acciones">
        <form method="POST" action="{{ route('reiniciar') }}">
            @csrf
            <button type="submit" class="btn-nuevo-dia">🌅 Iniciar Nuevo Día</button>
            <button type="submit" class="btn-nuevo-dia"onclick="window.print()" >🖨️ Imprimir Comprobante </button>
            <button type="submit" class="btn-nuevo-dia" onclick="descargarPDF()">💾 Guardar como PDF </button>
    </div>
        </form>
        
    <script>
    function descargarPDF() {
        window.print();
    }
    </script>
</main>

</body>
</html>