@extends('petugas.layout')

@section('title', 'Antrean Pengembalian')

@section('styles')
<style>
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

    .user-meta strong { display: block; color: var(--text-main); font-size: 0.9rem; }
    .user-meta small { color: var(--text-muted); font-size: 0.75rem; }

    /* Action Button */
    .btn-return {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: #eff6ff;
        color: #2563eb;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.8rem;
        transition: 0.2s;
    }

    .btn-return:hover {
        background: #2563eb;
        color: white;
        transform: translateY(-2px);
    }

    /* Badges */
    .badge-status {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
    }
    .status-ontime { background: #ecfdf5; color: #059669; }
    .status-late { background: #fff1f2; color: #e11d48; }

    .late-indicator {
        display: block;
        margin-top: 4px;
        color: #d97706;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        background: white;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
    }
</style>
@endsection

@section('content')
<div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-end;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 800; letter-spacing: -0.025em;">Pengembalian Alat</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem;">Daftar alat yang sedang dipinjam dan siap untuk diproses pengembaliannya.</p>
    </div>
    <div style="background: white; padding: 8px 16px; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 0.8rem; font-weight: 600; color: var(--text-muted);">
        Hari Ini: <span style="color: var(--text-main);">{{ now()->format('d M Y') }}</span>
    </div>
</div>

@if (session('success'))
    <div style="padding: 1rem; background: #ecfdf5; border: 1px solid #10b981; color: #065f46; border-radius: 12px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px;">
        <span>✅</span> {{ session('success') }}
    </div>
@endif

@if ($peminjaman->count() > 0)
    <div class="card" style="padding: 0; overflow: hidden; border-radius: 16px;">
        <div style="overflow-x: auto;">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Peminjam</th>
                        <th>Alat & Kategori</th>
                        <th>Jatuh Tempo</th>
                        <th>Keterangan</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($peminjaman as $p)
                        @php
                            $hari_keterlambatan = $p->calculateKeterlambatan();
                            $isLate = $hari_keterlambatan > 0;
                            $color = $isLate ? '#e11d48' : 'inherit';
                        @endphp
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
                                    <strong style="color: {{ $color }};">
                                        {{ $p->tanggal_kembali->format('d M Y') }}
                                    </strong>
                                    @if ($isLate)
                                        <span class="late-indicator">⏰ Terlambat {{ $hari_keterlambatan }} Hari</span>
                                    @else
                                        <small>Tersisa: {{ now()->diffInDays($p->tanggal_kembali) }} Hari lagi</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if ($isLate)
                                    <span class="badge-status status-late">Denda Aktif</span>
                                @else
                                    <span class="badge-status status-ontime">Tepat Waktu</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <a href="{{ route('petugas.pengembalian.form', $p->id) }}" class="btn-return">
                                    <span>↩</span> Terima Kembali
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
            {{ $peminjaman->links() }}
        </div>
    @endif
@else
    <div class="empty-state">
        <div style="font-size: 3rem; margin-bottom: 1rem;">📦</div>
        <h3 style="color: var(--text-main);">Tidak ada alat yang dipinjam</h3>
        <p style="color: var(--text-muted);">Semua peralatan saat ini tersedia di gudang atau belum ada peminjaman yang disetujui.</p>
        <a href="{{ route('petugas.dashboard') }}" style="display: inline-block; margin-top: 1.5rem; color: var(--primary); font-weight: 700; text-decoration: none;">Kembali ke Dashboard</a>
    </div>
@endif
@endsection