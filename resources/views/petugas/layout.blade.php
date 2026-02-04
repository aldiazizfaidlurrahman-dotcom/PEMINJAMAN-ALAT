<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Petugas Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #10b981; /* Emerald 500 */
            --primary-dark: #064e3b; /* Emerald 900 */
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
            --bg-body: #f8fafc;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-body); color: var(--text-main); display: flex; min-height: 100vh; overflow-x: hidden; }

        /* --- SIDEBAR --- */
        .sidebar { 
            width: var(--sidebar-width); 
            background-color: var(--primary-dark); 
            color: white; 
            display: flex; 
            flex-direction: column; 
            padding: 1.5rem 1rem; 
            position: fixed; 
            height: 100vh; 
            z-index: 1000; 
            transition: var(--transition);
            box-shadow: 4px 0 10px rgba(0,0,0,0.05);
        }

        /* Sidebar Collapsed State */
        body.collapsed .sidebar { width: var(--sidebar-collapsed-width); }
        body.collapsed .sidebar-brand span, 
        body.collapsed .nav-item span,
        body.collapsed .logout-text,
        body.collapsed .user-details { display: none; }
        
        .sidebar-brand { 
            display: flex; 
            align-items: center; 
            gap: 12px; 
            padding: 0.5rem 0.75rem;
            margin-bottom: 2.5rem; 
            color: white; 
            text-decoration: none; 
            overflow: hidden;
        }
        .sidebar-brand svg { flex-shrink: 0; }
        .sidebar-brand span { font-size: 1.1rem; font-weight: 800; letter-spacing: -0.5px; white-space: nowrap; }

        .nav-menu { flex-grow: 1; }
        .nav-item { 
            display: flex; 
            align-items: center; 
            gap: 12px; 
            padding: 12px 18px; 
            color: rgba(255,255,255,0.6); 
            text-decoration: none; 
            border-radius: 12px; 
            font-weight: 500; 
            margin-bottom: 0.5rem; 
            transition: var(--transition);
            white-space: nowrap;
        }
        .nav-item svg { flex-shrink: 0; }
        .nav-item:hover { color: white; background: rgba(255,255,255,0.05); }
        .nav-item.active { background: var(--primary); color: white; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); }

        /* Logout Bottom Section */
        .sidebar-footer {
            padding-top: 1rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        .btn-logout {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 18px;
            background: transparent;
            border: none;
            color: #fb7185; /* Rose 400 */
            cursor: pointer;
            font-family: inherit;
            font-weight: 600;
            border-radius: 12px;
            transition: var(--transition);
        }
        .btn-logout:hover { background: rgba(251, 113, 133, 0.1); }

        /* --- MAIN CONTENT --- */
        .main-wrapper { 
            margin-left: var(--sidebar-width); 
            width: calc(100% - var(--sidebar-width)); 
            padding: 2rem 3rem; 
            transition: var(--transition); 
        }
        body.collapsed .main-wrapper { 
            margin-left: var(--sidebar-collapsed-width); 
            width: calc(100% - var(--sidebar-collapsed-width)); 
        }

        /* Top Header */
        .top-header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 2.5rem; 
        }

        .toggle-sidebar {
            background: white;
            border: 1px solid #e2e8f0;
            padding: 8px;
            border-radius: 10px;
            cursor: pointer;
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.2s;
        }
        .toggle-sidebar:hover { background: #f1f5f9; color: var(--primary); }

        .header-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            background: white;
            padding: 6px 16px 6px 6px;
            border-radius: 99px;
            border: 1px solid #e2e8f0;
        }
        .avatar { 
            width: 36px; 
            height: 36px; 
            background: var(--primary); 
            color: white; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-weight: 700; 
            font-size: 0.8rem;
        }
        .user-details strong { font-size: 0.85rem; color: var(--text-main); display: block; }
        .user-details span { font-size: 0.7rem; color: var(--text-muted); font-weight: 600; }

        @media (max-width: 768px) {
            .sidebar { width: 0; padding: 0; overflow: hidden; }
            .main-wrapper { margin-left: 0; width: 100%; padding: 1.5rem; }
            body.collapsed .sidebar { width: var(--sidebar-collapsed-width); padding: 1.5rem 1rem; }
        }
    </style>
    @yield('styles')
</head>
<body class="">

    <aside class="sidebar">
        <div class="sidebar-brand">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m8 3 4 8 5-5 5 15H2L8 3z"/></svg>
            <span>LEND-IT TOOLS</span>
        </div>

        <nav class="nav-menu">
            <a href="{{ route('petugas.dashboard') }}" class="nav-item {{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('petugas.peminjaman.menunggu') }}" class="nav-item {{ request()->routeIs('petugas.peminjaman.menunggu') ? 'active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <span>Persetujuan</span>
            </a>
            <a href="{{ route('petugas.pengembalian.index') }}" class="nav-item {{ request()->routeIs('petugas.pengembalian.*') ? 'active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                <span>Pengembalian</span>
            </a>
            <a href="{{ route('petugas.peminjaman.index') }}" class="nav-item {{ request()->routeIs('petugas.peminjaman.index') ? 'active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                <span>Riwayat Pinjam</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    <span class="logout-text">Keluar Aplikasi</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="main-wrapper">
        <header class="top-header">
            <button class="toggle-sidebar" id="sidebarBtn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>

            <div class="header-profile">
                <div class="avatar">
                    {{ strtoupper(substr(session('pengguna_nama'), 0, 1)) }}
                </div>
                <div class="user-details">
                    <strong>{{ session('pengguna_nama') }}</strong>
                    <span>{{ session('pengguna_role') }}</span>
                </div>
            </div>
        </header>

        <div class="content-body">
            @yield('content')
        </div>
    </main>

    <script>
        const sidebarBtn = document.getElementById('sidebarBtn');
        const body = document.body;

        // Load preference
        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            body.classList.add('collapsed');
        }

        sidebarBtn.addEventListener('click', () => {
            body.classList.toggle('collapsed');
            localStorage.setItem('sidebar-collapsed', body.classList.contains('collapsed'));
        });
    </script>
    @yield('scripts')
</body>
</html>