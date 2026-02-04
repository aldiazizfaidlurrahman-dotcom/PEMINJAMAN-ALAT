@extends('admin.layout')

@section('title', 'Dashboard Admin')

@section('content')
<div class="dashboard-container">
    <div class="header-section">
        <div class="header-text">
            <h2>Ringkasan Statistik</h2>
            <p>Selamat datang kembali, berikut adalah status inventaris hari ini.</p>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-info">
                    <span class="stat-label">Total Pengguna</span>
                    <h3 class="stat-value">{{ \App\Models\Pengguna::count() }}</h3>
                </div>
                <div class="stat-icon icon-blue">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="stat-footer">
                <a href="{{ route('admin.pengguna.index') }}">Lihat Detail <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-info">
                    <span class="stat-label">Total Kategori</span>
                    <h3 class="stat-value">{{ \App\Models\Kategori::count() }}</h3>
                </div>
                <div class="stat-icon icon-purple">
                    <i class="fas fa-tags"></i>
                </div>
            </div>
            <div class="stat-footer">
                <a href="{{ route('admin.kategori.index') }}">Lihat Detail <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-info">
                    <span class="stat-label">Total Alat</span>
                    <h3 class="stat-value">{{ \App\Models\Alat::count() }}</h3>
                </div>
                <div class="stat-icon icon-orange">
                    <i class="fas fa-tools"></i>
                </div>
            </div>
            <div class="stat-footer">
                <a href="{{ route('admin.alat.index') }}">Lihat Detail <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-info">
                    <span class="stat-label">Unit Tersedia</span>
                    <h3 class="stat-value">{{ \App\Models\Alat::sum('stok') }}</h3>
                </div>
                <div class="stat-icon icon-green">
                    <i class="fas fa-box"></i>
                </div>
            </div>
            <div class="stat-footer no-link">
                <span>Stok saat ini</span>
            </div>
        </div>
    </div>
</div>

<style>
    /* Google Fonts Import - Jika belum ada di layout utama */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    /* Font Awesome Import - Untuk icon yang lebih bagus */
    @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

    body {
        background-color: #f8fafc;
        font-family: 'Inter', sans-serif;
        color: #1e293b;
    }

    .dashboard-container {
        padding: 20px;
    }

    .header-section {
        margin-bottom: 30px;
    }

    .header-text h2 {
        font-weight: 700;
        font-size: 1.5rem;
        margin: 0;
        color: #0f172a;
    }

    .header-text p {
        color: #64748b;
        margin-top: 5px;
        font-size: 0.9rem;
    }

    /* Grid System */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
    }

    /* Card Styling */
    .stat-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        border-color: #cbd5e1;
    }

    .stat-content {
        padding: 24px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .stat-info .stat-label {
        display: block;
        color: #64748b;
        font-size: 0.85rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        margin-bottom: 8px;
    }

    .stat-info .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0;
        color: #0f172a;
    }

    /* Icons */
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .icon-blue { background-color: #e0f2fe; color: #0284c7; }
    .icon-purple { background-color: #f5f3ff; color: #7c3aed; }
    .icon-orange { background-color: #fff7ed; color: #ea580c; }
    .icon-green { background-color: #f0fdf4; color: #16a34a; }

    /* Footer */
    .stat-footer {
        padding: 12px 24px;
        background-color: #f8fafc;
        border-top: 1px solid #e2e8f0;
        border-radius: 0 0 12px 12px;
    }

    .stat-footer a {
        text-decoration: none;
        color: #6366f1;
        font-size: 0.8rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: color 0.2s;
    }

    .stat-footer a:hover {
        color: #4338ca;
    }

    .stat-footer.no-link span {
        color: #94a3b8;
        font-size: 0.8rem;
    }

    /* Responsive */
    @media (max-width: 640px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection