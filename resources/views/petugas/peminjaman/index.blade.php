@extends('petugas.layout')

@section('title', 'Daftar Peminjaman')

@section('styles')
<style>
    /* Styling Tabs Filter */
    .filter-tabs {
        display: flex;
        gap: 12px;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .filter-tab {
        padding: 10px 20px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        text-decoration: none;
        color: var(--text-muted);
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-tab:hover {
        border-color: var(--primary);
        color: var(--primary);
    }

    .filter-tab.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.2);
    }

    /* Table Styling */
    .modern-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 16px;
        overflow: hidden;
    }

    .modern-table th {
        background: #f8fafc;
        padding: 16px;
        text-align: left;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-muted);
        border-bottom: 1px solid #e2e8f0;
    }

    .modern-table td {
        padding: 16px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
        vertical-align: middle;
    }

    .user-meta strong { display: block; color: var(--text-main); }
    .user-meta small { color: var(--text-muted); font-size: 0.75rem; }

    /* Badge Status */
    .badge {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
    }
    .bg-menunggu { background: #fffbeb; color: #d97706; }
    .bg-disetujui { background: #ecfdf5; color: #059669; }
    .bg-ditolak { background: #fef2f2; color: #ef4444; }
    .bg-dikembalikan { background: #eff6ff; color: #2563eb; }

    .btn-detail {
        padding: 8px 16px;
        background: #f1f5f9;
        color: var(--text-main);
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8rem;
        transition: 0.2s;
    }
    .btn-detail:hover { background: #e2e8f0; }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
    }
</style>
@endsection

@section('content')
<div style="margin-bottom: 2rem;">
    <h2 style="font-size: 1.5rem; font-weight: 800; letter-spacing: -0.025em;">Manajemen Peminjaman</h2>
    <p style="color: var(--text-muted); font-size: 0.9rem;">Kelola dan verifikasi permintaan peminjaman alat secara berkala.</p>
</div>

@if (session('success'))
    <div style="padding: 1rem; background: #ecfdf5; border: 1px solid #10b981; color: #065f46; border-radius: 12px; margin-bottom: 1.5rem;">
        {{ session('success') }}
    </div>
@endif

<div class="filter-tabs">
    @foreach ($validStatuses as $s)
        <a href="{{ route('petugas.peminjaman.index', ['status' => $s]) }}" 
           class="filter-tab {{ $status === $s ? 'active' : '' }}">
            <span>
                @switch($s)
                    @case('menunggu') ⏳ @break
                    @case('disetujui') ✅ @break
                    @case('ditolak') ❌ @break
                    @case('dikembalikan') ↩️ @break
                @endswitch
            </span>
            {{ ucfirst($s) }}
        </a>
    @endforeach
</div>

@if ($peminjaman->count() > 0)
    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="overflow-x: auto;">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Peminjam</th>
                        <th>Alat & Kategori</th>
                        <th>Periode Pinjam</th>
                        <th>Status</th>
                        <th style="text-align: right;">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($peminjaman as $p)
                        <tr>
                            <td style="color: var(--text-muted); font-weight: 600;">
                                {{ ($peminjaman->currentPage() - 1) * $peminjaman->perPage() + $loop->iteration }}
                            </td>
                            <td>
                                <div class="user-meta">
                                    <strong>{{ $p->pengguna->nama }}</strong>
                                    <small>{{ $p->pengguna->username }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="user-meta">
                                    <strong>{{ $p->alat->nama_alat }}</strong>
                                    <small>{{ $p->alat->kategori->nama_kategori }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="user-meta">
                                    <strong>{{ $p->tanggal_pinjam->format('d M Y') }}</strong>
                                    <small>s/d {{ $p->tanggal_kembali->format('d M Y') }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $p->status }}">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <a href="{{ route('petugas.peminjaman.approval', $p->id) }}" class="btn-detail">
                                    Detail & Aksi
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if ($peminjaman->hasPages())
        <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
            {{ $peminjaman->appends(['status' => $status])->links() }}
        </div>
    @endif
@else
    <div class="empty-state">
        <div style="font-size: 3rem; margin-bottom: 1rem;">📂</div>
        <h3 style="color: var(--text-main);">Tidak ada data</h3>
        <p style="color: var(--text-muted);">Belum ada riwayat peminjaman untuk status <b>{{ $status }}</b>.</p>
    </div>
@endif
@endsection