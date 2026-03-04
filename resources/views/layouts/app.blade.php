<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sistema Financiero TI</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <style>
        :root {
            --bg:         #f1f5f9;
            --surface:    #ffffff;
            --surface2:   #f8fafc;
            --border:     #e2e8f0;
            --border2:    #cbd5e1;
            --accent:     #1e40af;
            --accent-h:   #1d3a9e;
            --accent-lt:  #eff3ff;
            --green:      #15803d;
            --green-bg:   #f0fdf4;
            --green-bd:   #bbf7d0;
            --red:        #b91c1c;
            --red-bg:     #fef2f2;
            --red-bd:     #fecaca;
            --yellow:     #92400e;
            --yellow-bg:  #fffbeb;
            --yellow-bd:  #fde68a;
            --blue-bg:    #eff6ff;
            --blue-bd:    #bfdbfe;
            --text:       #0f172a;
            --text2:      #475569;
            --muted:      #94a3b8;
            --sidebar-w:  256px;
            --radius:     8px;
            --shadow-sm:  0 1px 2px rgba(15,23,42,.05);
            --shadow:     0 1px 3px rgba(15,23,42,.08), 0 1px 8px rgba(15,23,42,.04);
        }

        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Inter',sans-serif; background:var(--bg); color:var(--text); min-height:100vh; display:flex; font-size:14px; -webkit-font-smoothing:antialiased; }
        button, input, select, textarea { font-family:'Inter',sans-serif; }

        /* ── SIDEBAR ── */
        .sidebar {
            width: var(--sidebar-w);
            background: #0f172a;
            display: flex; flex-direction: column;
            position: fixed; height: 100vh; z-index: 20;
        }
        .sidebar-logo {
            padding: 20px 20px 18px;
            border-bottom: 1px solid rgba(255,255,255,.07);
            display: flex; align-items: center; gap: 10px;
        }
        .logo-mark {
            width: 32px; height: 32px; flex-shrink: 0;
            background: var(--accent); border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
        }
        .logo-mark svg { width:16px; height:16px; stroke:#fff; fill:none; stroke-width:2.5; }
        .logo-text { font-size: 14px; font-weight: 700; color: #fff; letter-spacing:-.2px; }
        .logo-sub  { font-size: 10px; color: rgba(255,255,255,.35); margin-top: 1px; }

        .sidebar-nav { flex:1; padding: 14px 12px; display:flex; flex-direction:column; gap:1px; overflow-y:auto; }
        .nav-section {
            font-size: 10px; text-transform: uppercase; letter-spacing: 1px;
            color: rgba(255,255,255,.25); padding: 14px 8px 5px; font-weight: 600;
        }
        .nav-item {
            display: flex; align-items: center; gap: 9px;
            padding: 8px 10px; border-radius: 6px;
            font-size: 13px; font-weight: 500;
            color: rgba(255,255,255,.5);
            cursor: pointer; transition: all .15s; text-decoration: none;
        }
        .nav-item svg { width:15px; height:15px; stroke:currentColor; fill:none; stroke-width:1.8; flex-shrink:0; }
        .nav-item:hover { background: rgba(255,255,255,.06); color: rgba(255,255,255,.9); }
        .nav-item.active { background: rgba(30,64,175,.5); color: #93c5fd; }
        .nav-item.active svg { stroke: #93c5fd; }
        .nav-badge {
            margin-left: auto; font-size: 10px; font-weight: 700;
            background: var(--accent); color: #fff;
            padding: 1px 7px; border-radius: 10px; line-height: 16px;
        }
        .sidebar-footer {
            padding: 14px 16px; border-top: 1px solid rgba(255,255,255,.07);
            display: flex; align-items: center; gap: 10px;
        }
        .avatar {
            width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 700; color: #fff; letter-spacing:.5px;
        }
        .user-name  { font-size: 12px; font-weight: 600; color: rgba(255,255,255,.85); }
        .user-role  { font-size: 10px; color: rgba(255,255,255,.3); margin-top:1px; }
        .logout-btn {
            margin-left: auto; background: none; border: none; cursor: pointer;
            color: rgba(255,255,255,.3); transition: color .15s; padding:4px;
        }
        .logout-btn svg { width:14px; height:14px; stroke:currentColor; fill:none; stroke-width:2; }
        .logout-btn:hover { color: rgba(255,255,255,.7); }

        /* ── MAIN ── */
        .main { margin-left: var(--sidebar-w); flex:1; display:flex; flex-direction:column; min-height:100vh; }

        /* ── TOPBAR ── */
        .topbar {
            background: var(--surface); border-bottom: 1px solid var(--border);
            padding: 0 28px; height: 58px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 10;
            box-shadow: var(--shadow-sm);
        }
        .page-title { font-size: 15px; font-weight: 700; color: var(--text); }
        .topbar-right { display: flex; align-items: center; gap: 10px; }
        .topbar-date {
            font-size: 12px; color: var(--muted);
            font-family: 'JetBrains Mono', monospace;
            background: var(--surface2); border: 1px solid var(--border);
            padding: 5px 11px; border-radius: var(--radius);
        }
        .btn-primary {
            background: var(--accent); color: #fff;
            padding: 7px 16px; border-radius: var(--radius);
            font-size: 12px; font-weight: 600; border: none; cursor: pointer;
            display: inline-flex; align-items: center; gap: 6px;
            box-shadow: 0 1px 3px rgba(30,64,175,.3);
            transition: all .15s; text-decoration: none;
        }
        .btn-primary svg { width:13px; height:13px; stroke:#fff; fill:none; stroke-width:2.5; }
        .btn-primary:hover { background: var(--accent-h); }

        /* ── PAGE CONTENT ── */
        .page-content { padding: 28px; flex:1; }

        /* ── PAGE HEADER ── */
        .page-header { margin-bottom: 22px; }
        .page-header h2 { font-size: 18px; font-weight: 700; color: var(--text); letter-spacing:-.3px; }
        .page-header p  { font-size: 13px; color: var(--muted); margin-top: 3px; }

        /* ── KPI CARDS ── */
        .kpi-grid   { display:grid; gap:16px; margin-bottom:24px; }
        .kpi-3 { grid-template-columns:repeat(3,1fr); }
        .kpi-4 { grid-template-columns:repeat(4,1fr); }
        .kpi-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius); padding: 18px 20px;
            box-shadow: var(--shadow);
            display: flex; flex-direction: column; gap: 12px;
        }
        .kpi-top  { display:flex; align-items:center; justify-content:space-between; }
        .kpi-body { display:flex; flex-direction:column; gap:3px; }
        .kpi-icon {
            width: 36px; height: 36px; border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
        }
        .kpi-icon svg { width:17px; height:17px; stroke-width:1.8; fill:none; }
        .kpi-icon.green  { background:var(--green-bg);  border:1px solid var(--green-bd);  } .kpi-icon.green  svg { stroke:var(--green); }
        .kpi-icon.red    { background:var(--red-bg);    border:1px solid var(--red-bd);    } .kpi-icon.red    svg { stroke:var(--red); }
        .kpi-icon.blue   { background:var(--blue-bg);   border:1px solid var(--blue-bd);   } .kpi-icon.blue   svg { stroke:var(--accent); }
        .kpi-icon.yellow { background:var(--yellow-bg); border:1px solid var(--yellow-bd); } .kpi-icon.yellow svg { stroke:var(--yellow); }
        .kpi-trend {
            font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 5px;
            display:flex; align-items:center; gap:3px;
        }
        .kpi-trend svg { width:10px; height:10px; stroke:currentColor; fill:none; stroke-width:2.5; }
        .kpi-trend.up   { background:var(--green-bg);  color:var(--green);  }
        .kpi-trend.down { background:var(--red-bg);    color:var(--red);    }
        .kpi-trend.neu  { background:var(--surface2);  color:var(--muted);  }
        .kpi-label { font-size:11px; color:var(--muted); font-weight:500; text-transform:uppercase; letter-spacing:.5px; }
        .kpi-value { font-size:22px; font-weight:700; font-family:'JetBrains Mono',monospace; letter-spacing:-1px; line-height:1.1; }
        .kpi-value.green  { color:var(--green); }
        .kpi-value.red    { color:var(--red); }
        .kpi-value.blue   { color:var(--accent); }
        .kpi-value.yellow { color:var(--yellow); }
        .kpi-sub { font-size:11px; color:var(--muted); }

        /* ── CARD ── */
        .card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow);
        }
        .card-header {
            padding: 14px 20px; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            background: var(--surface2);
        }
        .card-title   { font-size: 13px; font-weight: 700; color: var(--text); }
        .card-actions { display:flex; gap:8px; align-items:center; }

        /* ── TABS ── */
        .tabs { display:flex; padding:0 20px; border-bottom:1px solid var(--border); background:var(--surface2); }
        .tab {
            padding:11px 14px; font-size:12px; font-weight:600; color:var(--muted);
            cursor:pointer; border-bottom:2px solid transparent; margin-bottom:-1px; transition:all .15s;
        }
        .tab:hover { color:var(--text); }
        .tab.active { color:var(--accent); border-bottom-color:var(--accent); }

        /* ── SEARCH ── */
        .search-wrap { position:relative; }
        .search-wrap svg { position:absolute; left:10px; top:50%; transform:translateY(-50%); width:14px; height:14px; stroke:var(--muted); fill:none; stroke-width:2; pointer-events:none; }
        .search-input {
            background: var(--surface); border: 1px solid var(--border2);
            border-radius: var(--radius); padding: 7px 12px 7px 32px;
            font-size: 12px; color: var(--text); outline: none; width: 220px;
            transition: border-color .15s;
        }
        .search-input:focus { border-color: var(--accent); }
        .search-input::placeholder { color: var(--muted); }

        /* ── DATATABLES ── */
        .dataTables_wrapper { padding: 0; }
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_length { display:none; }
        .dataTables_wrapper .dataTables_info {
            font-size:11px; color:var(--muted); padding:10px 20px; border-top:1px solid var(--border);
        }
        .dataTables_wrapper .dataTables_paginate {
            padding:8px 20px; border-top:1px solid var(--border);
            display:flex; justify-content:flex-end; align-items:center; gap:3px;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            font-size:12px; font-weight:500; padding:5px 10px; border-radius:5px;
            border:1px solid var(--border) !important; color:var(--text2) !important;
            background:var(--surface) !important; cursor:pointer; transition:all .15s;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background:var(--accent-lt) !important; border-color:var(--accent) !important; color:var(--accent) !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background:var(--accent) !important; border-color:var(--accent) !important; color:#fff !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled { opacity:.4; cursor:default; }

        table.dataTable { width:100% !important; border-collapse:collapse !important; }
        table.dataTable thead th {
            padding:10px 16px; font-size:10px; text-transform:uppercase; letter-spacing:.8px;
            color:var(--muted); font-weight:700; background:var(--surface2);
            border-bottom:1px solid var(--border) !important; white-space:nowrap;
        }
        table.dataTable tbody td {
            padding:11px 16px; font-size:13px; color:var(--text);
            border-bottom:1px solid var(--border) !important; vertical-align:middle;
        }
        table.dataTable tbody tr:last-child td { border-bottom:none !important; }
        table.dataTable tbody tr:hover td { background:var(--surface2); }
        table.dataTable.no-footer { border-bottom:none; }

        /* ── BADGES ── */
        .badge {
            display:inline-flex; align-items:center; gap:5px;
            padding:3px 9px; border-radius:4px; font-size:11px; font-weight:600; border:1px solid;
        }
        .badge-dot { width:5px; height:5px; border-radius:50%; flex-shrink:0; }
        .badge.green  { background:var(--green-bg);  color:var(--green);  border-color:var(--green-bd);  } .badge.green  .badge-dot { background:var(--green); }
        .badge.red    { background:var(--red-bg);    color:var(--red);    border-color:var(--red-bd);    } .badge.red    .badge-dot { background:var(--red); }
        .badge.yellow { background:var(--yellow-bg); color:var(--yellow); border-color:var(--yellow-bd); } .badge.yellow .badge-dot { background:var(--yellow); }
        .badge.blue   { background:var(--blue-bg);   color:var(--accent); border-color:var(--blue-bd);   } .badge.blue   .badge-dot { background:var(--accent); }

        /* ── AMOUNTS ── */
        .amount     { font-family:'JetBrains Mono',monospace; font-size:13px; font-weight:600; }
        .amount.pos { color:var(--green); }
        .amount.neg { color:var(--red); }
        .id-cell    { font-family:'JetBrains Mono',monospace; font-size:11px; color:var(--muted); }

        /* ── BTN SM ── */
        .btn-sm {
            padding:4px 11px; border-radius:5px; font-size:11px; font-weight:600; cursor:pointer;
            border:1px solid var(--border2); color:var(--text2); background:var(--surface);
            transition:all .15s; display:inline-flex; align-items:center; gap:5px; white-space:nowrap;
        }
        .btn-sm svg { width:12px; height:12px; stroke:currentColor; fill:none; stroke-width:2; }
        .btn-sm:hover        { border-color:var(--accent); color:var(--accent); background:var(--blue-bg); }
        .btn-sm.danger:hover { border-color:var(--red);    color:var(--red);    background:var(--red-bg); }
        .btn-sm.success      { background:var(--green-bg); border-color:var(--green-bd); color:var(--green); }

        /* ── FORM ── */
        .form-grid    { display:grid; grid-template-columns:1fr 1fr; gap:18px; padding:22px; }
        .form-col-2   { grid-column:span 2; }
        .form-group label {
            display:block; font-size:11px; color:var(--text2); margin-bottom:5px;
            text-transform:uppercase; letter-spacing:.5px; font-weight:600;
        }
        .form-control {
            width:100%; background:var(--surface); border:1px solid var(--border2);
            border-radius:var(--radius); padding:9px 12px; color:var(--text);
            font-size:13px; outline:none; transition:border-color .15s, box-shadow .15s;
        }
        .form-control:focus { border-color:var(--accent); box-shadow:0 0 0 3px rgba(30,64,175,.07); }
        .form-control::placeholder { color:var(--muted); }
        .form-footer {
            padding:14px 22px; border-top:1px solid var(--border);
            display:flex; justify-content:flex-end; gap:8px; background:var(--surface2);
        }
        .btn-cancel {
            padding:8px 18px; border-radius:var(--radius); font-size:13px; font-weight:600;
            background:var(--surface); border:1px solid var(--border2); color:var(--text2); cursor:pointer;
        }
        .btn-save {
            padding:8px 20px; border-radius:var(--radius); font-size:13px; font-weight:600;
            background:var(--accent); border:none; color:#fff; cursor:pointer;
        }

        /* ── DT CONTROLS ── */
        .dt-controls {
            display:flex; align-items:center; justify-content:space-between;
            padding:12px 20px; border-bottom:1px solid var(--border);
        }

        /* ── MODAL ── */
        .modal-overlay {
            position:fixed; inset:0; background:rgba(15,23,42,.45);
            display:none; align-items:center; justify-content:center; z-index:50;
            backdrop-filter: blur(2px);
        }
        .modal-overlay.open { display:flex; }
        .modal-box {
            background:var(--surface); border-radius:12px; box-shadow:0 20px 60px rgba(15,23,42,.2);
            width:100%; max-width:480px; overflow:hidden; border:1px solid var(--border);
        }
        .modal-header {
            padding:16px 22px; border-bottom:1px solid var(--border);
            display:flex; align-items:center; justify-content:space-between;
            background:var(--surface2);
        }
        .modal-title { font-size:14px; font-weight:700; color:var(--text); }
        .modal-close {
            background:none; border:none; cursor:pointer; color:var(--muted);
            padding:4px; border-radius:4px; transition:color .15s;
        }
        .modal-close:hover { color:var(--text); }
        .modal-close svg { width:16px; height:16px; stroke:currentColor; fill:none; stroke-width:2; }
        .modal-body   { padding:22px; display:flex; flex-direction:column; gap:16px; }
        .modal-footer {
            padding:14px 22px; border-top:1px solid var(--border);
            display:flex; justify-content:flex-end; gap:8px; background:var(--surface2);
        }
    </style>
</head>

<body>

{{-- SIDEBAR --}}
<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-mark">
            <svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        </div>
        <div>
            <div class="logo-text">Sistema TI</div>
            <div class="logo-sub">ERP Financiero</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">General</div>
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
            Dashboard
        </a>

        <div class="nav-section">Finanzas</div>
        <a href="{{ route('movimientos.index') }}" class="nav-item {{ request()->routeIs('movimientos.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
            Movimientos
        </a>
        <a href="{{ route('documentos.index') }}" class="nav-item {{ request()->routeIs('documentos.*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24">
            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
        </svg>
        Documentos
        </a>

        <a href="{{ route('pagos.index') }}" class="nav-item {{ request()->routeIs('pagos.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24">
                <rect x="1" y="4" width="22" height="16" rx="2"/>
                <line x1="1" y1="10" x2="23" y2="10"/>
            </svg>
            Pagos
        </a>
        
        <div class="nav-section">Administración</div>
        <a href="{{ route('empresas.index') }}" class="nav-item {{ request()->routeIs('empresas.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Empresas
        </a>
        <a href="{{ route('terceros.index') }}" class="nav-item {{ request()->routeIs('terceros.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
            Terceros
        </a>
        <a href="{{ route('usuarios.index') }}" class="nav-item {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Usuarios
        </a>
        <a href="#" class="nav-item">
            <svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
            Reportes
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
        <div>
            <div class="user-name">{{ Auth::user()->name }}</div>
            <div class="user-role">{{ Auth::user()->rol->nombre ?? 'Usuario' }}</div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn" title="Cerrar sesión">
                <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            </button>
        </form>
    </div>
</aside>

{{-- MAIN --}}
<main class="main">

    {{-- TOPBAR --}}
    <header class="topbar">
        <div class="page-title">{{ $header ?? 'Dashboard' }}</div>
        <div class="topbar-right">
            <span class="topbar-date">{{ now()->format('d M Y') }}</span>
            @isset($topbarAction)
                {{ $topbarAction }}
            @endisset
        </div>
    </header>

    {{-- PAGE --}}
    <div class="page-content">
        {{ $slot }}
    </div>

</main>

</body>
</html>