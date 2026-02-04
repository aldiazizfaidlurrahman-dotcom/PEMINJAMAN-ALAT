@extends('peminjam.layout')

@section('title', 'Riwayat Peminjaman')

@section('styles')
<style>
    /* Filter Section Refinement */
    .filter-card {
        background: white;
        padding: 1.25rem 1.5rem;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .filter-label {
        font-size: 0.875rem;
        font-weight: 700;
        color: var(--text-main);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-select {
        padding: 8px 36px 8px 12px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        font-family: inherit;
        font-size: 0.875rem;
        color: var(--text-main);
        background-color: #fff;
        cursor: pointer;
        transition: 0.2s;
    }
    .form-select:focus { border-color: var(--primary); outline: none; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); }

    /* Modern Table Styling */
    .table-card {
        background: white;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .modern-table { width: 100%; border-collapse: collapse; }
    .modern-table th {
        background: #f8fafc;
        padding: 16px 20px;
        text-align: left;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-muted);
        border-bottom: 1px solid #e2e8f0;
    }
    .modern-table td {
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
        vertical-align: middle;
    }
    .modern-table tr:last-child td { border-bottom: none; }

    /* Item Meta Info */
    .item-info strong { display: block; color: var(--text-main); font-weight: 700; }
    .item-info small { color: var(--text-muted); font-size: 0.75rem; }

    /* Status Pills */
    .status-pill {
        display: inline-flex;
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: capitalize;
    }
    .status-menunggu { background: #fffbeb; color: #d97706; }
    .status-disetujui { background: #ecfdf5; color: #059669; }
    .status-ditolak { background: #fef2f2; color: #e11d48; }
    .status-dikembalikan { background: #eff6ff; color: #2563eb; }

    /* Action Buttons */
    .btn-action {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: 0.2s;
        text-decoration: none;
    }
    .btn-view { background: #f1f5f9; color: #475569; }
    .btn-view:hover { background: #e2e8f0; color: var(--primary); }
    .btn-cancel { background: #fff1f2; color: #e11d48; }
    .btn-cancel:hover { background: #ffe4e6; }

    /* Empty State */
    .empty-container { padding: 4rem 2rem; text-align: center; }
</style>
@endsection

@section('content')
<div style="margin-bottom: 2rem;">
    <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; letter-spacing: -0.025em;">Riwayat Peminjaman</h2>
    <p style="color: var(--text-muted); font-size: 0.9rem;">Pantau status permohonan dan kelola jadwal peminjaman alat Anda.</p>
</div>

{{-- Alert Messages --}}
@if (session('success'))
    <div style="padding: 1rem 1.5rem; background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 12px; color: #065f46; margin-bottom: 1.5rem; font-size: 0.875rem; font-weight: 500; display: flex; align-items: center; gap: 10px;">
        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
        {{ session('success') }}
    </div>
@endif

{{-- Filter Status --}}
<div class="filter-card">
    <div class="filter-label">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
        Filter Status
    </div>
    <form method="GET" action="{{ route('peminjam.peminjaman.index') }}">
        <select name="status" class="form-select" onchange="this.form.submit()">
            <option value="">Semua Riwayat</option>
            @foreach ($statuses as $key => $value)
                <option value="{{ $key }}" @if($status === $key) selected @endif>
                    {{ $value }}
                </option>
            @endforeach
        </select>
    </form>
</div>

{{-- Tabel Peminjaman --}}
<div class="table-card">
    <div style="overflow-x: auto;">
        <table class="modern-table">
            <thead>
                <tr>
                    <th style="width: 60px;">No.</th>
                    <th>Detail Alat</th>
                    <th>Tanggal Pinjam</th>
                    <th>Jatuh Tempo</th>
                    <th>Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($peminjaman as $item)
                    <tr>
                        <td style="color: var(--text-muted); font-weight: 600;">
                            {{ ($peminjaman->currentPage() - 1) * $peminjaman->perPage() + $loop->iteration }}
                        </td>
                        <td>
                            <div class="item-info">
                                <strong>{{ $item->alat->nama_alat }}</strong>
                                <small>{{ $item->alat->kategori->nama_kategori }}</small>
                            </div>
                        </td>
                        <td style="color: var(--text-main); font-weight: 500;">
                            {{ $item->tanggal_pinjam->format('d M Y') }}
                        </td>
                        <td style="color: var(--text-main); font-weight: 500;">
                            {{ $item->tanggal_kembali->format('d M Y') }}
                        </td>
                        <td>
                            <span class="status-pill status-{{ $item->status }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                <a href="{{ route('peminjam.peminjaman.show', $item->id) }}" class="btn-action btn-view" title="Lihat Detail">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                                @if ($item->status === 'menunggu')
                                    <form action="{{ route('peminjam.peminjaman.cancel', $item->id) }}" method="POST" onsubmit="return confirm('Batalkan peminjaman ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-cancel" title="Batalkan">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-container">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 1rem;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                <p style="color: var(--text-muted); font-weight: 500;">Belum ada data peminjaman yang ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Pagination --}}
@if ($peminjaman->hasPages())
    <div style="margin-top: 2rem; display: flex; justify-content: center;">
        {{ $peminjaman->appends(request()->query())->links() }}
    </div>
@endif

@endsection