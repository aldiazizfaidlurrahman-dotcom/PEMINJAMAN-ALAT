<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1e3a8a;
            --primary-light: #eff6ff;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
            --bg-body: #f8fafc;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --border: #e2e8f0;
            --radius: 12px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: var(--sidebar-width);
            background-color: white;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            padding: 1.5rem 0.75rem;
            position: fixed;
            height: 100vh;
            z-index: 1000;
            transition: var(--transition);
        }

        body.collapsed .sidebar {
            width: var(--sidebar-collapsed-width);
        }

        body.collapsed .sidebar-brand span,
        body.collapsed .nav-item span,
        body.collapsed .logout-text {
            display: none;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.5rem 0.75rem;
            margin-bottom: 2rem;
            color: var(--primary);
            text-decoration: none;
            overflow: hidden;
        }

        .brand-icon {
            background: var(--primary);
            padding: 7px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .sidebar-brand span {
            font-size: 1.1rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            white-space: nowrap;
            color: var(--text-main);
        }

        .nav-menu {
            flex-grow: 1;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 10px;
            font-weight: 500;
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
            transition: var(--transition);
            white-space: nowrap;
        }

        .nav-item svg {
            width: 19px;
            height: 19px;
            flex-shrink: 0;
            stroke-width: 2;
        }

        .nav-item:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .nav-item.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
        }

        /* --- LOGOUT SECTION (Minimalist) --- */
        .sidebar-footer {
            padding: 0.75rem 0.5rem;
            border-top: 1px solid var(--border);
            margin-top: 0.5rem;
        }

        .btn-logout {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            background: transparent;
            border: none;
            color: #f43f5e;
            /* Rose 500 */
            cursor: pointer;
            font-family: inherit;
            font-weight: 600;
            font-size: 0.85rem;
            border-radius: 8px;
            transition: var(--transition);
        }

        .btn-logout svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }

        .btn-logout:hover {
            background: #fff1f2;
        }

        /* --- MAIN CONTENT --- */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            transition: var(--transition);
        }

        body.collapsed .main-wrapper {
            margin-left: var(--sidebar-collapsed-width);
            width: calc(100% - var(--sidebar-collapsed-width));
        }

        .top-header {
            height: 70px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .toggle-sidebar {
            background: var(--primary-light);
            border: 1px solid #dbeafe;
            padding: 7px;
            border-radius: 8px;
            cursor: pointer;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.2s;
        }

        .toggle-sidebar:hover {
            background: var(--primary);
            color: white;
        }

        .header-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            background: white;
            padding: 5px 12px 5px 5px;
            border-radius: 99px;
            border: 1px solid var(--border);
        }

        .avatar {
            width: 30px;
            height: 30px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.75rem;
        }

        .user-info strong {
            font-size: 0.8rem;
            color: var(--text-main);
            display: block;
            line-height: 1.2;
        }

        .user-info span {
            font-size: 0.65rem;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
        }

        .content-body {
            padding: 1.5rem 2rem;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 1.25rem;
        }

        .breadcrumb a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .alert-success {
            background: #f0fdf4;
            color: #166534;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            border: 1px solid #bbf7d0;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                padding: 0;
                overflow: hidden;
            }

            .main-wrapper {
                margin-left: 0;
                width: 100%;
            }

            body.collapsed .sidebar {
                width: var(--sidebar-width);
                padding: 1.5rem 1rem;
            }

            body.collapsed .sidebar-brand span,
            body.collapsed .nav-item span {
                display: inline;
            }
        }
    </style>
    @yield('styles')
</head>

<body id="body">

    <aside class="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <div class="brand-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m8 3 4 8 5-5 5 15H2L8 3z" />
                </svg>
            </div>
            <span>Admin<span style="color:var(--primary)">Panel</span></span>
        </a>

        <nav class="nav-menu">
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.pengguna.index') }}" class="nav-item {{ str_contains(Route::currentRouteName(), 'admin.pengguna') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span>Data Pengguna</span>
            </a>
            <a href="{{ route('admin.dataPeminjaman') }}" class="nav-item {{ str_contains(Route::currentRouteName(), 'admin.dataPeminjaman') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Histori Peminjaman</span>
            </a>
            <a href="{{ route('admin.dataPengembalian') }}" class="nav-item {{ str_contains(Route::currentRouteName(), 'admin.dataPengembalian') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7v4a2 2 0 01-2 2H7a2 2 0 01-2-2V7m5 4V7m4 4V7" />
                </svg>
                <span>Histori Pengembalian</span>
            </a>
            <a href="{{ route('admin.kategori.index') }}" class="nav-item {{ str_contains(Route::currentRouteName(), 'admin.kategori') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                </svg>
                <span>Kategori Alat</span>
            </a>
            <a href="{{ route('admin.alat.index') }}" class="nav-item {{ str_contains(Route::currentRouteName(), 'admin.alat') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 011-1V4z" />
                </svg>
                <span>Manajemen Alat</span>
            </a>
            <a href="{{ route('admin.logAktivitas') }}" class="nav-item {{ str_contains(Route::currentRouteName(), 'admin.logAktivitas') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Log Aktivitas</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="logout-text">Keluar Sistem</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="main-wrapper">
        <header class="top-header">
            <button class="toggle-sidebar" id="sidebarBtn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12" />
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <line x1="3" y1="18" x2="21" y2="18" />
                </svg>
            </button>

            <div class="header-profile">
                <div class="avatar">
                    {{ strtoupper(substr(session('pengguna_nama', 'A'), 0, 1)) }}
                </div>
                <div class="user-info">
                    <strong>{{ session('pengguna_nama', 'Administrator') }}</strong>
                    <span>{{ session('pengguna_role', 'Admin') }}</span>
                </div>
            </div>
        </header>

        <div class="content-body">
            <div class="breadcrumb">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m9 18 6-6-6-6" />
                </svg>
                <span>@yield('breadcrumb', 'Halaman')</span>
            </div>

            @if (session('success'))
            <div class="alert-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 6 9 17l-5-5" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script>
        const sidebarBtn = document.getElementById('sidebarBtn');
        const body = document.body;

        if (localStorage.getItem('admin-sidebar-collapsed') === 'true') {
            body.classList.add('collapsed');
        }

        sidebarBtn.addEventListener('click', () => {
            body.classList.toggle('collapsed');
            localStorage.setItem('admin-sidebar-collapsed', body.classList.contains('collapsed'));
        });
    </script>
    @yield('scripts')
</body>

</html>