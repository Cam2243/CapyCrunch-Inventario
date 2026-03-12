<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CapyCrunch — Inventario del Día</title>
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
            --red-lt:   #e07a5f;
            --surface:  #fff8ee;
            --border:   #e8d5b5;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: var(--cream);
            color: var(--text);
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background-image:
                radial-gradient(ellipse 60% 40% at 10% 0%, rgba(200,129,58,0.08) 0%, transparent 60%),
                radial-gradient(ellipse 50% 30% at 90% 100%, rgba(200,129,58,0.06) 0%, transparent 60%);
        }

        .topbar {
            background: var(--brown);
            color: var(--warm);
            padding: 0.85rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky; top: 0; z-index: 100;
            box-shadow: 0 2px 20px rgba(74,44,10,0.25);
        }

        .brand {
            display: flex; align-items: center; gap: 0.75rem;
        }

        .brand-icon {
            font-size: 1.6rem; line-height: 1;
        }

        .brand-text { display: flex; flex-direction: column; }

        .brand-name {
            font-family: 'Fraunces', serif;
            font-size: 1.1rem; font-weight: 700;
            color: var(--caramel2); line-height: 1;
        }

        .brand-sub {
            font-size: 0.65rem; font-weight: 500;
            color: rgba(245,230,204,0.6);
            letter-spacing: 0.08em; text-transform: uppercase;
        }

        .topbar-right {
            display: flex; align-items: center; gap: 1rem;
        }

        .fecha-badge {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 999px;
            padding: 0.3rem 0.85rem;
            font-size: 0.75rem; color: var(--warm);
            font-weight: 500;
        }

        .btn-cierre {
            background: var(--caramel);
            color: #fff;
            border: none; border-radius: 8px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.8rem; font-weight: 700;
            padding: 0.5rem 1.1rem; cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            display: inline-flex; align-items: center; gap: 0.4rem;
        }

        .btn-cierre:hover { background: var(--caramel2); }

        main {
            max-width: 1100px;
            margin: 0 auto;
            padding: 2rem 1.5rem 4rem;
        }

        .alert {
            border-radius: 10px;
            padding: 0.85rem 1.2rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem; font-weight: 500;
            display: flex; align-items: center; gap: 0.6rem;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .alert-success { background: #eaf7f0; border: 1px solid #a7d7bc; color: var(--green); }
        .alert-error   { background: #fef0ec; border: 1px solid #f0b9aa; color: var(--red); }

        .apertura-section {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .section-header {
            display: flex; align-items: center; gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .section-icon {
            width: 40px; height: 40px;
            background: var(--warm);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
        }

        .section-title {
            font-family: 'Fraunces', serif;
            font-size: 1.2rem; font-weight: 600; color: var(--brown);
        }

        .section-sub { font-size: 0.8rem; color: var(--text3); margin-top: 0.1rem; }

        .apertura-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .apertura-item {
            background: var(--cream);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
        }

        .apertura-item .emoji { font-size: 1.8rem; margin-bottom: 0.4rem; display: block; }
        .apertura-item .nombre { font-size: 0.78rem; font-weight: 600; color: var(--text); margin-bottom: 0.6rem; }
        .apertura-item .precio-hint { font-size: 0.7rem; color: var(--text3); margin-bottom: 0.6rem; }

        .apertura-item input[type="number"] {
            width: 100%;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.1rem; font-weight: 700;
            padding: 0.5rem;
            text-align: center;
            transition: border-color 0.2s;
        }

        .apertura-item input:focus {
            outline: none; border-color: var(--caramel);
        }

        .btn-apertura {
            background: var(--brown);
            color: var(--cream);
            border: none; border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.9rem; font-weight: 700;
            padding: 0.8rem 2rem; cursor: pointer;
            transition: background 0.2s, transform 0.1s;
        }

        .btn-apertura:hover { background: var(--brown2); }
        .btn-apertura:active { transform: scale(0.98); }

        .dia-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 1.5rem;
            align-items: start;
        }

        .stock-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stock-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.25rem;
            position: relative;
            transition: box-shadow 0.2s;
        }

        .stock-card:hover { box-shadow: 0 4px 20px rgba(74,44,10,0.1); }

        .stock-card .sc-emoji { font-size: 2rem; display: block; margin-bottom: 0.5rem; }
        .stock-card .sc-nombre {
            font-size: 0.82rem; font-weight: 600; color: var(--text);
            margin-bottom: 0.3rem; line-height: 1.3;
        }
        .stock-card .sc-precio { font-size: 0.72rem; color: var(--text3); margin-bottom: 0.75rem; }

        .stock-bar-wrap {
            background: var(--warm);
            border-radius: 999px; height: 6px;
            overflow: hidden; margin-bottom: 0.6rem;
        }

        .stock-bar {
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(to right, var(--caramel), var(--caramel2));
            transition: width 0.4s ease;
        }

        .stock-bar.low { background: linear-gradient(to right, var(--red), var(--red-lt)); }

        .sc-nums {
            display: flex; justify-content: space-between;
            font-size: 0.7rem;
        }

        .sc-actual {
            font-size: 1.4rem; font-weight: 700;
            color: var(--brown); font-family: 'Fraunces', serif;
        }

        .sc-actual.low { color: var(--red); }
        .sc-vendidas { font-size: 0.7rem; color: var(--green); font-weight: 600; }
        .sc-inicio { color: var(--text3); }

        .venta-panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.5rem;
            position: sticky; top: 70px;
        }

        .vp-title {
            font-family: 'Fraunces', serif;
            font-size: 1.1rem; font-weight: 600; color: var(--brown);
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 0.5rem;
        }

        .vp-select {
            width: 100%;
            background: var(--cream);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.9rem; font-weight: 500;
            padding: 0.7rem 1rem;
            margin-bottom: 1rem;
            transition: border-color 0.2s;
            -webkit-appearance: none;
            cursor: pointer;
        }

        .vp-select:focus { outline: none; border-color: var(--caramel); }

        .vp-cant-wrap {
            display: flex; gap: 0.5rem; margin-bottom: 1rem;
            align-items: center;
        }

        .vp-cant-btn {
            width: 38px; height: 38px;
            background: var(--warm);
            border: 1px solid var(--border);
            border-radius: 8px; font-size: 1.1rem; font-weight: 700;
            color: var(--brown); cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: background 0.15s;
        }

        .vp-cant-btn:hover { background: var(--border); }

        .vp-cant-input {
            flex: 1;
            background: var(--cream);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-family: 'Fraunces', serif;
            font-size: 1.3rem; font-weight: 700;
            padding: 0.4rem; text-align: center;
            width: 100%;
        }

        .vp-cant-input:focus { outline: none; border-color: var(--caramel); }

        .btn-vender {
            width: 100%;
            background: var(--green);
            color: #fff; border: none; border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.95rem; font-weight: 700;
            padding: 0.85rem; cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        }

        .btn-vender:hover { background: #245a42; }
        .btn-vender:active { transform: scale(0.98); }

        .historial {
            margin-top: 1.5rem;
            border-top: 1px solid var(--border);
            padding-top: 1rem;
        }

        .historial-title {
            font-size: 0.7rem; font-weight: 600;
            letter-spacing: 0.1em; text-transform: uppercase;
            color: var(--text3); margin-bottom: 0.75rem;
        }

        .historial-list {
            display: flex; flex-direction: column; gap: 0.4rem;
            max-height: 220px; overflow-y: auto;
        }

        .historial-list::-webkit-scrollbar { width: 4px; }
        .historial-list::-webkit-scrollbar-thumb { background: var(--border); border-radius: 2px; }

        .h-item {
            display: flex; align-items: center; gap: 0.5rem;
            background: var(--cream);
            border-radius: 8px; padding: 0.45rem 0.7rem;
            font-size: 0.78rem;
        }

        .h-hora { color: var(--text3); font-size: 0.68rem; min-width: 50px; }
        .h-desc { flex: 1; color: var(--text); font-weight: 500; }
        .h-monto { color: var(--green); font-weight: 700; }

        .historial-empty {
            text-align: center; padding: 1rem;
            color: var(--text3); font-size: 0.8rem;
        }

        .add-producto-section {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px; padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .add-grid {
            display: grid; grid-template-columns: 2fr 1fr 0.6fr auto;
            gap: 0.75rem; align-items: end;
        }

        .add-group { display: flex; flex-direction: column; gap: 0.4rem; }

        .add-label { font-size: 0.72rem; font-weight: 600; color: var(--text3); letter-spacing: 0.05em; text-transform: uppercase; }

        .add-input {
            background: var(--cream);
            border: 1px solid var(--border);
            border-radius: 9px;
            color: var(--text);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.9rem; padding: 0.6rem 0.85rem;
            transition: border-color 0.2s; width: 100%;
        }

        .add-input:focus { outline: none; border-color: var(--caramel); }

        .btn-add {
            background: var(--caramel);
            color: #fff; border: none; border-radius: 9px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.85rem; font-weight: 700;
            padding: 0.6rem 1.1rem; cursor: pointer;
            white-space: nowrap;
            transition: background 0.2s;
        }

        .btn-add:hover { background: var(--caramel2); }

        .stats-row {
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 1rem; margin-bottom: 1.5rem;
        }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px; padding: 1rem 1.25rem;
        }

        .stat-label { font-size: 0.7rem; font-weight: 600; color: var(--text3); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.3rem; }

        .stat-value {
            font-family: 'Fraunces', serif;
            font-size: 1.6rem; font-weight: 600; color: var(--brown); line-height: 1;
        }

        .stat-value.green { color: var(--green); }
        .stat-sub { font-size: 0.72rem; color: var(--text3); margin-top: 0.2rem; }

        @media (max-width: 750px) {
            .dia-grid { grid-template-columns: 1fr; }
            .add-grid { grid-template-columns: 1fr 1fr; }
            .stats-row { grid-template-columns: 1fr 1fr; }
            .venta-panel { position: static; }
        }

        @media (max-width: 480px) {
            .topbar { padding: 0.75rem 1rem; }
            .stats-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="topbar">
    <div class="brand">
        <span class="brand-icon">🐾</span>
        <div class="brand-text">
            <span class="brand-name">CapyCrunch</span>
            <span class="brand-sub">Inventario & Caja</span>
        </div>
    </div>
    <div class="topbar-right">
        @if($diaAbierto)
            <span class="fecha-badge">📅 {{ $fecha }}</span>
            <a href="{{ route('cierre') }}" class="btn-cierre">🧾 Cierre de Caja</a>
        @endif
    </div>
</div>

<main>

    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">⚠️ {{ session('error') }}</div>
    @endif

    @if(!$diaAbierto)

        <div class="add-producto-section">
            <div class="section-header" style="margin-bottom:1rem;">
                <div class="section-icon">➕</div>
                <div>
                    <div class="section-title">Gestionar Catálogo</div>
                    <div class="section-sub">Agrega nuevas galletas antes de abrir el día</div>
                </div>
            </div>
            <form method="POST" action="{{ route('producto.agregar') }}">
                @csrf
                <div class="add-grid">
                    <div class="add-group">
                        <label class="add-label">Nombre del producto</label>
                        <input class="add-input" type="text" name="nombre" placeholder="Ej: Galleta de Coco" required>
                    </div>
                    <div class="add-group">
                        <label class="add-label">Precio ($)</label>
                        <input class="add-input" type="number" name="precio" placeholder="3500" min="1" required>
                    </div>
                    <div class="add-group">
                        <label class="add-label">Emoji</label>
                        <select class="add-input" name="emoji" cursor:pointer">
                                        <option value="🍪">🍪 Galleta clásica</option>
                                        <option value="🍫">🍫 Chocolate</option>
                                        <option value="🌰">🌰 Canela</option>
                                        <option value="🍋">🍋 Limón</option>
                                        <option value="🌾">🌾 Avena</option>
                                        <option value="🥥">🥥 Coco</option>
                                        <option value="🍓">🍓 Fresa</option>
                                        <option value="🫐">🫐 Arándano</option>
                                        <option value="🍑">🍑 Durazno</option>
                                        <option value="🥜">🥜 Maní</option>
                                        <option value="🍯">🍯 Miel</option>
                                        <option value="🎂">🎂 Torta</option>
                                        <option value="🧁">🧁 Cupcake</option>
                                        <option value="🍩">🍩 Donut</option>
                                        <option value="🐾">🐾 Capybara</option>
                                    </select>
                    </div>
                    <button type="submit" class="btn-add">+ Agregar</button>
                </div>
            </form>
        </div>

        <div class="apertura-section">
            <div class="section-header">
                <div class="section-icon">🌅</div>
                <div>
                    <div class="section-title">Apertura del Día</div>
                    <div class="section-sub">Ingresa cuántas unidades de cada galleta tienes hoy</div>
                </div>
            </div>

            <form method="POST" action="{{ route('apertura') }}">
                @csrf
                <div class="apertura-grid">
                    @foreach($catalogo as $p)
                    <div class="apertura-item">
                        <span class="emoji">{{ $p['emoji'] }}</span>
                        <div class="nombre">{{ $p['nombre'] }}</div>
                        <div class="precio-hint">$ {{ number_format($p['precio'], 0, ',', '.') }} c/u</div>
                        <input type="number" name="stock_{{ $p['id'] }}" value="0" min="0" placeholder="0">
                    </div>
                    @endforeach
                </div>
                <button type="submit" class="btn-apertura">🌅 Abrir el día con este stock</button>
            </form>
        </div>

    @else

        {{-- Estadísticas rápidas --}}
        @php
            $totalVendidas = array_sum($ventas);
            $totalRecaudado = 0;
            foreach($catalogo as $p) {
                $totalRecaudado += ($ventas[$p['id']] ?? 0) * $p['precio'];
            }
            $totalStock = array_sum($inventario);
        @endphp

        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-label">💰 Recaudado hoy</div>
                <div class="stat-value green">$ {{ number_format($totalRecaudado, 0, ',', '.') }}</div>
                <div class="stat-sub">en ventas del día</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">🍪 Unidades vendidas</div>
                <div class="stat-value">{{ $totalVendidas }}</div>
                <div class="stat-sub">galletas vendidas</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">📦 Stock restante</div>
                <div class="stat-value">{{ $totalStock }}</div>
                <div class="stat-sub">unidades disponibles</div>
            </div>
        </div>

        <div class="dia-grid">

            <div>
                <div class="stock-grid">
                    @foreach($catalogo as $p)
                    @php
                        $actual   = $inventario[$p['id']] ?? 0;
                        $inicio   = $apertura[$p['id']] ?? 1;
                        $vendidas = $ventas[$p['id']] ?? 0;
                        $pct      = $inicio > 0 ? ($actual / $inicio) * 100 : 0;
                        $low      = $pct <= 20;
                    @endphp
                    <div class="stock-card">
                        <span class="sc-emoji">{{ $p['emoji'] }}</span>
                        <div class="sc-nombre">{{ $p['nombre'] }}</div>
                        <div class="sc-precio">$ {{ number_format($p['precio'], 0, ',', '.') }} c/u</div>
                        <div class="stock-bar-wrap">
                            <div class="stock-bar {{ $low ? 'low' : '' }}" style="width: {{ min(100, $pct) }}%"></div>
                        </div>
                        <div class="sc-nums">
                            <span class="sc-actual {{ $low ? 'low' : '' }}">{{ $actual }}</span>
                            <span class="sc-inicio">/ {{ $inicio }}</span>
                        </div>
                        @if($vendidas > 0)
                        <div class="sc-vendidas">✓ {{ $vendidas }} vendidas</div>
                        @endif
                    </div>
                    @endforeach
                </div>

                <div class="add-producto-section">
                    <div class="section-header" style="margin-bottom:1rem;">
                        <div class="section-icon">➕</div>
                        <div>
                            <div class="section-title" style="font-size:1rem;">Agregar Producto Nuevo</div>
                            <div class="section-sub">Se iniciará con stock 0 para este día</div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('producto.agregar') }}">
                        @csrf
                        <div class="add-grid">
                            <div class="add-group">
                                <label class="add-label">Nombre</label>
                                <input class="add-input" type="text" name="nombre" placeholder="Galleta de Coco" required>
                            </div>
                            <div class="add-group">
                                <label class="add-label">Precio ($)</label>
                                <input class="add-input" type="number" name="precio" placeholder="3500" min="1" required>
                            </div>
                            <div class="add-group">
                                <label class="add-label">Emoji</label>
                                <select class="add-input" name="emoji" style="font-size:1.2rem; cursor:pointer">
                                        <option value="🍪">🍪 Galleta clásica</option>
                                        <option value="🍫">🍫 Chocolate</option>
                                        <option value="🌰">🌰 Canela</option>
                                        <option value="🍋">🍋 Limón</option>
                                        <option value="🌾">🌾 Avena</option>
                                        <option value="🥥">🥥 Coco</option>
                                        <option value="🍓">🍓 Fresa</option>
                                        <option value="🫐">🫐 Arándano</option>
                                        <option value="🍑">🍑 Durazno</option>
                                        <option value="🥜">🥜 Maní</option>
                                        <option value="🍯">🍯 Miel</option>
                                        <option value="🎂">🎂 Torta</option>
                                        <option value="🧁">🧁 Cupcake</option>
                                        <option value="🍩">🍩 Donut</option>
                                        <option value="🐾">🐾 Capybara</option>
                                    </select>
                            </div>
                            <button type="submit" class="btn-add">+ Agregar</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="venta-panel">
                <div class="vp-title">🛒 Registrar Venta</div>

                <form method="POST" action="{{ route('venta.registrar') }}">
                    @csrf
                    <select name="producto_id" class="vp-select" required>
                        <option value="">— Selecciona una galleta —</option>
                        @foreach($catalogo as $p)
                        <option value="{{ $p['id'] }}">
                            {{ $p['emoji'] }} {{ $p['nombre'] }} ({{ $inventario[$p['id']] ?? 0 }} disp.)
                        </option>
                        @endforeach
                    </select>

                    <div class="vp-cant-wrap">
                        <button type="button" class="vp-cant-btn" onclick="cambiarCant(-1)">−</button>
                        <input type="number" name="cantidad" id="cantInput" class="vp-cant-input" value="1" min="1" required>
                        <button type="button" class="vp-cant-btn" onclick="cambiarCant(1)">+</button>
                    </div>

                    <button type="submit" class="btn-vender">
                        ✓ Registrar Venta
                    </button>
                </form>

                <div class="historial">
                    <div class="historial-title">Historial del día</div>
                    @if(count($historial) > 0)
                    <div class="historial-list">
                        @foreach(array_reverse($historial) as $h)
                        <div class="h-item">
                            <span class="h-hora">{{ $h['hora'] }}</span>
                            <span>{{ $h['emoji'] }}</span>
                            <span class="h-desc">{{ $h['cantidad'] }}× {{ $h['producto'] }}</span>
                            <span class="h-monto">+${{ number_format($h['subtotal'], 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="historial-empty">Sin ventas aún hoy 🍪</div>
                    @endif
                </div>
            </div>

        </div>
    @endif

</main>

<script>
function cambiarCant(delta) {
    const input = document.getElementById('cantInput');
    const val = parseInt(input.value) || 1;
    input.value = Math.max(1, val + delta);
}
</script>

</body>
</html>