@extends('peminjam.layout')

@section('title', 'Dashboard')

@section('styles')
<style>
    /* Welcome Banner */
    .welcome-banner {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
        border-radius: 24px;
        padding: 2.5rem;
        color: white;
        margin-bottom: 2.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2);
    }
    .welcome-text h2 { font-size: 1.8rem; font-weight: 800; margin-bottom: 0.5rem; }
    .welcome-text p { opacity: 0.9; font-size: 1rem; }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }
    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        transition: transform 0.3s ease;
    }
    .stat-card:hover { transform: translateY(-5px); }
    
    .icon-shape {
        width: 56px; height: 56px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
    }
    .icon-indigo { background: #eef2ff; color: #6366f1; }
    .icon-amber { background: #fffbeb; color: #d97706; }
    .icon-emerald { background: #ecfdf5; color: #10b981; }

    .stat-info label { display: block; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; }
    .stat-info span { font-size: 1.75rem; font-weight: 800; color: var(--text-main); line-height: 1; }

    /* Action Grid */
    .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; }
    .section-header h3 { font-size: 1.25rem; font-weight: 800; color: #0f172a; }

    .action-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2.5rem; }
    .action-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        text-decoration: none;
        transition: all 0.3s;
    }
    .action-card:hover { border-color: var(--primary); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); }
    .action-card .btn-circle { width: 48px; height: 48px; border-radius: 50%; background: var(--bg-body); color: var(--primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: 0.3s; }
    .action-card:hover .btn-circle { background: var(--primary); color: white; }
    .action-card h4 { font-size: 1rem; font-weight: 700; color: var(--text-main); margin-bottom: 2px; }
    .action-card p { font-size: 0.85rem; color: var(--text-muted); }

    /* Recent Table Style */
    .recent-list { background: white; border-radius: 20px; border: 1px solid #e2e8f0; overflow: hidden; }
    .recent-item { 
        display: flex; align-items: center; justify-content: space-between; 
        padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; 
    }
    .recent-item:last-child { border-bottom: none; }
    .alat-info h5 { font-size: 0.95rem; font-weight: 700; color: var(--text-main); }
    .alat-info p { font-size: 0.8rem; color: var(--text-muted); }

    .status-pill {
        padding: 6px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 700;
    }
    .status-menunggu { background: #fffbeb; color: #d97706; }
    .status-disetujui { background: #ecfdf5; color: #059669; }
    .status-ditolak { background: #fef2f2; color: #e11d48; }
    .status-dikembalikan { background: #eff6ff; color: #2563eb; }

    @media (max-width: 1024px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 640px) {
        .stats-grid, .action-grid { grid-template-columns: 1fr; }
        .welcome-banner { flex-direction: column; text-align: center; gap: 1.5rem; }
    }
</style>
@endsection

@section('content')
<div class="welcome-banner">
    <div class="welcome-text">
        <h2>Selamat Datang, {{ session('pengguna_nama') }}! 👋</h2>
        <p>Kelola peminjaman alat inventaris Anda dengan sistem yang lebih cepat dan aman.</p>
    </div>
    <div style="background: rgba(255,255,255,0.1); padding: 1.5rem; border-radius: 50%;">
        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="icon-shape icon-amber">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div class="stat-info">
            <label>Menunggu</label>
            <span>{{ \App\Models\Peminjaman::where('pengguna_id', session('pengguna_id'))->where('status', 'menunggu')->count() }}</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="icon-shape icon-indigo">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <div class="stat-info">
            <label>Disetujui</label>
            <span>{{ \App\Models\Peminjaman::where('pengguna_id', session('pengguna_id'))->where('status', 'disetujui')->count() }}</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="icon-shape icon-emerald">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 17 4 12 9 7"/><path d="M20 18v-2a4 4 0 0 0-4-4H4"/></svg>
        </div>
        <div class="stat-info">
            <label>Kembali</label>
            <span>{{ \App\Models\Peminjaman::where('pengguna_id', session('pengguna_id'))->where('status', 'dikembalikan')->count() }}</span>
        </div>
    </div>
</div>

<div class="section-header">
    <h3>Aksi Cepat</h3>
</div>
<div class="action-grid">
    <a href="{{ route('peminjam.alat.index') }}" class="action-card">
        <div class="btn-circle">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </div>
        <div>
            <h4>Cari Alat</h4>
            <p>Jelajahi katalog alat inventaris</p>
        </div>
    </a>
    <a href="{{ route('peminjam.peminjaman.index') }}" class="action-card">
        <div class="btn-circle">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
        </div>
        <div>
            <h4>Riwayat Saya</h4>
            <p>Pantau status permohonan pinjam</p>
        </div>
    </a>
</div>

@php
    $recentPeminjaman = \App\Models\Peminjaman::with('alat')
        ->where('pengguna_id', session('pengguna_id'))
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
@endphp

@if ($recentPeminjaman->isNotEmpty())
<div class="section-header">
    <h3>Aktivitas Terakhir</h3>
    <a href="{{ route('peminjam.peminjaman.index') }}" style="font-size: 0.85rem; color: var(--primary); font-weight: 700; text-decoration: none;">Lihat Semua</a>
</div>
<div class="recent-list">
    @foreach ($recentPeminjaman as $item)
    <div class="recent-item">
        <div class="alat-info">
            <h5>{{ $item->alat->nama_alat }}</h5>
            <p>{{ $item->tanggal_pinjam->format('d M Y') }} — {{ $item->tanggal_kembali->format('d M Y') }}</p>
        </div>
        <div class="recent-status">
            <span class="status-pill status-{{ $item->status }}">
                {{ ucfirst($item->status) }}
            </span>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection