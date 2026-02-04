<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Peminjam Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1; /* Indigo 500 */
            --primary-dark: #4338ca; /* Indigo 700 */
            --primary-light: #eef2ff; /* Indigo 50 */
            --sidebar-bg: #1e1b4b; /* Indigo 950 (Sangat Gelap) */
            --bg-body: #f8fafc;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-body); color: var(--text-main); display: flex; min-height: 100vh; overflow-x: hidden; }

        /* --- SIDEBAR --- */
        .sidebar { 
            width: var(--sidebar-width); 
            background-color: var(--sidebar-bg); 
            color: white; 
            display: flex; 
            flex-direction: column; 
            padding: 1.5rem 1rem; 
            position: fixed; 
            height: 100vh; 
            z-index: 1000; 
            transition: var(--transition);
        }

        body.collapsed .sidebar { width: var(--sidebar-collapsed-width); }
        body.collapsed .sidebar-brand span, 
        body.collapsed .nav-item span,
        body.collapsed .logout-text,
        body.collapsed .user-tag { display: none; }
        
        .sidebar-brand { 
            display: flex; 
            align-items: center; 
            gap: 12px; 
            padding: 0.5rem 0.75rem;
            margin-bottom: 2.5rem; 
            color: white; 
            text-decoration: none; 
        }
        .sidebar-brand span { font-size: 1.25rem; font-weight: 800; letter-spacing: -0.5px; white-space: nowrap; }

        .nav-menu { flex-grow: 1; }
        .nav-item { 
            display: flex; 
            align-items: center; 
            gap: 12px; 
            padding: 12px 18px; 
            color: rgba(255,255,255,0.5); 
            text-decoration: none; 
            border-radius: 12px; 
            font-weight: 500; 
            margin-bottom: 0.5rem; 
            transition: var(--transition);
            white-space: nowrap;
        }
        .nav-item:hover { color: white; background: rgba(255,255,255,0.05); }
        .nav-item.active { background: var(--primary); color: white; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4); }

        /* Sidebar Footer (Logout) */
        .sidebar-footer { padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.1); }
        .btn-logout {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 18px;
            background: transparent;
            border: none;
            color: #fda4af; /* Rose 300 */
            cursor: pointer;
            font-family: inherit;
            font-weight: 600;
            border-radius: 12px;
            transition: var(--transition);
        }
        .btn-logout:hover { background: rgba(253, 164, 175, 0.1); }

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

        .btn-toggle {
            background: white;
            border: 1px solid #e2e8f0;
            padding: 10px;
            border-radius: 12px;
            cursor: pointer;
            color: var(--text-main);
            display: flex;
            transition: 0.2s;
        }
        .btn-toggle:hover { background: var(--primary-light); color: var(--primary); }

        .header-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            background: white;
            padding: 6px 16px 6px 6px;
            border-radius: 99px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .avatar { 
            width: 38px; height: 38px; 
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white; border-radius: 50%; 
            display: flex; align-items: center; justify-content: center; 
            font-weight: 700; font-size: 0.9rem;
        }
        .user-meta strong { font-size: 0.875rem; display: block; color: var(--text-main); }
        .user-tag { font-size: 0.7rem; color: var(--primary); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }

        /* General Card */
        .card { background: white; border-radius: 20px; border: 1px solid #e2e8f0; padding: 2rem; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            body.show-sidebar .sidebar { transform: translateX(0); width: var(--sidebar-width); }
            .main-wrapper { margin-left: 0 !important; width: 100% !important; padding: 1.5rem; }
        }
    </style>
    @yield('styles')
</head>
<body>

    <aside class="sidebar">
        <a href="{{ route('peminjam.dashboard') }}" class="sidebar-brand">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
            <span>BORROW HUB</span>
        </a>

        <nav class="nav-menu">
            <a href="{{ route('peminjam.dashboard') }}" class="nav-item {{ request()->routeIs('peminjam.dashboard') ? 'active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('peminjam.alat.index') }}" class="nav-item {{ request()->routeIs('peminjam.alat.*') ? 'active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <span>Cari & Pinjam</span>
            </a>
            <a href="{{ route('peminjam.peminjaman.index') }}" class="nav-item {{ request()->routeIs('peminjam.peminjaman.*') ? 'active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                <span>Riwayat Pinjam</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    <span class="logout-text">Keluar Sesi</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="main-wrapper">
        <header class="top-header">
            <button class="btn-toggle" id="btnToggle">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>

            <div class="header-profile">
                <div class="avatar">
                    {{ strtoupper(substr(session('pengguna_nama'), 0, 1)) }}
                </div>
                <div class="user-meta">
                    <strong>{{ session('pengguna_nama') }}</strong>
                    <span class="user-tag">Peminjam</span>
                </div>
            </div>
        </header>

        <div class="content-body">
            @yield('content')
        </div>
    </main>

    <script>
        const btnToggle = document.getElementById('btnToggle');
        const body = document.body;

        // Cek status terakhir dari local storage
        if (localStorage.getItem('peminjam-sidebar-collapsed') === 'true') {
            body.classList.add('collapsed');
        }

        btnToggle.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                body.classList.toggle('show-sidebar');
            } else {
                body.classList.toggle('collapsed');
                localStorage.setItem('peminjam-sidebar-collapsed', body.classList.contains('collapsed'));
            }
        });
    </script>
    @yield('scripts')
</body>
</html>