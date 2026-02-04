@extends('peminjam.layout')

@section('title', 'Katalog Alat')

@section('styles')
<style>
    /* Search Bar Modernization */
    .search-container {
        background: white;
        padding: 1.5rem;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        margin-bottom: 2.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    }
    
    .search-form {
        display: flex;
        gap: 12px;
    }

    .input-group {
        position: relative;
        flex-grow: 1;
    }

    .input-group svg {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
    }

    .search-input {
        width: 100%;
        padding: 12px 16px 12px 44px;
        border-radius: 12px;
        border: 1px solid #cbd5e1;
        font-family: inherit;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    .search-input:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    .btn-search {
        padding: 0 24px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: 0.2s;
    }
    .btn-search:hover { background: var(--primary-dark); }

    /* Alat Grid & Cards */
    .alat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .alat-card {
        background: white;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
    }

    .alat-card:hover {
        transform: translateY(-8px);
        border-color: var(--primary);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
    }

    .alat-header {
        padding: 1.5rem;
        padding-bottom: 0.5rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .alat-header h3 {
        font-size: 1.1rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        line-height: 1.4;
    }

    .alat-body {
        padding: 0 1.5rem 1.5rem 1.5rem;
        flex-grow: 1;
    }

    .info-tag {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-top: 8px;
    }

    .stok-status {
        margin-top: 1rem;
        padding: 10px 14px;
        background: var(--bg-body);
        border-radius: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stok-status label { font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; }
    .stok-status span { font-weight: 800; color: var(--text-main); }

    /* Badges */
    .badge-pill {
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }
    .badge-baik { background: #ecfdf5; color: #059669; }
    .badge-rusak { background: #fffbeb; color: #d97706; }

    /* Footer Button */
    .alat-footer {
        padding: 1rem 1.5rem 1.5rem 1.5rem;
    }

    .btn-pinjam {
        display: block;
        width: 100%;
        text-align: center;
        padding: 12px;
        background: var(--primary-light);
        color: var(--primary);
        text-decoration: none;
        border-radius: 12px;
        font-weight: 800;
        font-size: 0.875rem;
        transition: 0.2s;
    }
    .btn-pinjam:hover {
        background: var(--primary);
        color: white;
    }
    .btn-disabled {
        background: #f1f5f9;
        color: #94a3b8;
        cursor: not-allowed;
    }

    @media (max-width: 640px) {
        .search-form { flex-direction: column; }
        .btn-search { padding: 12px; }
    }
</style>
@endsection

@section('content')
<div style="margin-bottom: 2rem;">
    <h2 style="font-size: 1.75rem; font-weight: 800; color: #0f172a; letter-spacing: -0.025em;">Eksplorasi Peralatan</h2>
    <p style="color: var(--text-muted); font-size: 0.95rem;">Temukan dan pinjam peralatan terbaik untuk mendukung kebutuhan Anda.</p>
</div>

{{-- Search Box --}}
<div class="search-container">
    <form method="GET" action="{{ route('peminjam.alat.index') }}" class="search-form">
        <div class="input-group">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="search" class="search-input" placeholder="Ketik nama alat (ex: Proyektor, Kamera...)" value="{{ $search }}">
        </div>
        <button type="submit" class="btn-search">Cari Alat</button>
        @if($search)
            <a href="{{ route('peminjam.alat.index') }}" style="display:flex; align-items:center; padding:0 12px; color: var(--text-muted); text-decoration:none; font-size:0.8rem; font-weight:700;">Reset</a>
        @endif
    </form>
</div>

@if (session('success'))
    <div style="padding: 1rem 1.5rem; background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 12px; color: #065f46; margin-bottom: 2rem; font-size: 0.875rem; font-weight: 500;">
        {{ session('success') }}
    </div>
@endif

{{-- Grid Alat --}}
<div class="alat-grid">
    @forelse ($alat as $item)
        <div class="alat-card">
            <div class="alat-header">
                <h3>{{ $item->nama_alat }}</h3>
                <span class="badge-pill badge-{{ $item->kondisi }}">
                    {{ $item->kondisi }}
                </span>
            </div>

            <div class="alat-body">
                <div class="info-tag">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                    {{ $item->kategori->nama_kategori }}
                </div>

                <div class="stok-status">
                    <label>Ketersediaan</label>
                    <span>{{ $item->stok }} <small style="font-weight:400; color:var(--text-muted);">Unit</small></span>
                </div>
            </div>

            <div class="alat-footer">
                @if ($item->stok > 0)
                    <a href="{{ route('peminjam.alat.form', $item->id) }}" class="btn-pinjam">
                        Ajukan Peminjaman
                    </a>
                @else
                    <div class="btn-pinjam btn-disabled">
                        Stok Tidak Tersedia
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem; background: white; border-radius: 24px; border: 1px solid #e2e8f0;">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 1rem;"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            <p style="color: var(--text-muted); font-weight: 500;">Peralatan yang Anda cari tidak ditemukan.</p>
        </div>
    @endforelse
</div>

{{-- Pagination --}}
<div style="margin-top: 3rem; display: flex; justify-content: center;">
    {{ $alat->appends(['search' => $search])->links() }}
</div>

@endsection