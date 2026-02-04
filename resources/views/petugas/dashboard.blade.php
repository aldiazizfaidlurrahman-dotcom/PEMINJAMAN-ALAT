@extends('petugas.layout')

@section('title', 'Dashboard')

@section('styles')
<style>
    /* Hero Section Refinement */
    .hero-banner { 
        background: white; 
        border-radius: 24px; 
        padding: 2.5rem; 
        margin-bottom: 2.5rem; 
        border: 1px solid #e2e8f0; 
        display: flex; 
        justify-content: space-between; 
        align-items: center;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .hero-text h2 { 
        font-size: 1.875rem; 
        font-weight: 800; 
        color: #0f172a;
        margin-bottom: 0.75rem; 
        letter-spacing: -0.025em;
    }
    .hero-text p { 
        color: var(--text-muted); 
        font-size: 1rem; 
        line-height: 1.6;
        max-width: 500px;
    }
    .hero-text b {
        color: var(--primary);
        background: var(--primary-light);
        padding: 2px 8px;
        border-radius: 6px;
    }
    
    /* Stats Grid Refinement */
    .stats-grid { 
        display: grid; 
        grid-template-columns: repeat(4, 1fr); 
        gap: 1.5rem; 
    }
    .stat-card { 
        background: white; 
        padding: 1.5rem; 
        border-radius: 20px; 
        border: 1px solid #e2e8f0; 
        text-decoration: none; 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
    }
    .stat-card:hover { 
        transform: translateY(-8px); 
        border-color: var(--primary);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
    }
    
    .icon-box { 
        width: 52px; 
        height: 52px; 
        border-radius: 14px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        margin-bottom: 1.25rem; 
    }
    .icon-box svg {
        width: 24px;
        height: 24px;
    }

    .stat-card h4 { 
        font-size: 0.875rem; 
        color: var(--text-muted); 
        margin-bottom: 0.5rem; 
        font-weight: 600;
        text-transform: uppercase; 
        letter-spacing: 0.05em; 
    }
    .stat-card .value { 
        font-size: 2.25rem; 
        font-weight: 800; 
        color: var(--text-main); 
        letter-spacing: -0.05em;
    }
    
    /* Color Palette */
    .c-wait { background: #fffbeb; color: #d97706; } /* Amber */
    .c-success { background: #ecfdf5; color: #059669; } /* Emerald */
    .c-danger { background: #fef2f2; color: #e11d48; } /* Rose */
    .c-return { background: #eff6ff; color: #2563eb; } /* Blue */

    @media (max-width: 1280px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 640px) {
        .stats-grid { grid-template-columns: 1fr; }
        .hero-banner { flex-direction: column; text-align: center; gap: 2rem; }
    }
</style>
@endsection

@section('content')
<section class="hero-banner">
    <div class="hero-text">
        <h2>Selamat Datang, {{ session('pengguna_nama') }}!</h2>
        <p>Sistem mencatat ada <b>{{ $peminjamanMenunggu }}</b> permintaan peminjaman baru yang memerlukan validasi Anda segera.</p>
    </div>
    <div class="hero-icon">
        <div style="background: var(--bg-body); padding: 2rem; border-radius: 50%;">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
    </div>
</section>

<div class="stats-grid">
    <a href="{{ route('petugas.peminjaman.menunggu') }}" class="stat-card">
        <div class="icon-box c-wait">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <h4>Menunggu</h4>
        <div class="value">{{ $peminjamanMenunggu }}</div>
    </a>

    <a href="{{ route('petugas.peminjaman.index', ['status' => 'disetujui']) }}" class="stat-card">
        <div class="icon-box c-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <h4>Pinjam Aktif</h4>
        <div class="value">{{ $peminjamanDisetujui }}</div>
    </a>

    <a href="{{ route('petugas.peminjaman.index', ['status' => 'ditolak']) }}" class="stat-card">
        <div class="icon-box c-danger">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        </div>
        <h4>Ditolak</h4>
        <div class="value">{{ $peminjamanDitolak }}</div>
    </a>

    <a href="{{ route('petugas.pengembalian.index') }}" class="stat-card">
        <div class="icon-box c-return">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 17 4 12 9 7"/><path d="M20 18v-2a4 4 0 0 0-4-4H4"/></svg>
        </div>
        <h4>Total Kembali</h4>
        <div class="value">{{ $peminjamanDisetujui }}</div>
    </a>
</div>
@endsection