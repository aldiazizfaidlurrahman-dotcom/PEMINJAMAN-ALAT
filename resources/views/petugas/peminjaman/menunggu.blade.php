@extends('petugas.layout')

@section('title', 'Persetujuan Masuk')

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
    .btn-process {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: var(--primary-light);
        color: var(--primary);
        text-decoration: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.8rem;
        transition: 0.2s;
    }

    .btn-process:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
    }

    /* Badge Label */
    .badge-wait {
        background: #fffbeb;
        color: #d97706;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
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
        <h2 style="font-size: 1.5rem; font-weight: 800; letter-spacing: -0.025em;">Persetujuan Peminjaman</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem;">Berikut adalah daftar permintaan peminjaman alat yang memerlukan validasi Anda.</p>
    </div>
    <div style="background: white; padding: 8px 16px; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 0.8rem; font-weight: 600; color: var(--text-muted);">
        Total Antrean: <span style="color: var(--primary);">{{ $peminjaman->total() }}</span>
    </div>
</div>

@if (session('success'))
    <div style="padding: 1rem; background: #ecfdf5; border: 1px solid #10b981; color: #065f46; border-radius: 12px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px;">
        <span>✅</span> {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div style="padding: 1rem; background: #fef2f2; border: 1px solid #ef4444; color: #991b1b; border-radius: 12px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px;">
        <span>⚠️</span> {{ session('error') }}
    </div>
@endif

@if ($peminjaman->count() > 0)
    <div class="card" style="padding: 0; overflow: hidden; border-radius: 16px;">
        <div style="overflow-x: auto;">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Identitas Peminjam</th>
                        <th>Alat & Kategori</th>
                        <th>Rencana Pinjam</th>
                        <th>Status</th>
                        <th style="text-align: right;">Aksi</th>
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
                                    <small>{{ $p->pengguna->username }} • {{ $p->pengguna->email }}</small>
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
                                    <small>Durasi: {{ $p->tanggal_pinjam->diffInDays($p->tanggal_kembali) + 1 }} Hari</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge-wait">⏳ Menunggu</span>
                            </td>
                            <td style="text-align: right;">
                                <a href="{{ route('petugas.peminjaman.approval', $p->id) }}" class="btn-process">
                                    <span>✓</span> Proses
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
        <div style="font-size: 3rem; margin-bottom: 1rem;">🎉</div>
        <h3 style="color: var(--text-main);">Antrean Kosong</h3>
        <p style="color: var(--text-muted);">Tidak ada permintaan peminjaman yang perlu diproses saat ini.</p>
        <a href="{{ route('petugas.dashboard') }}" style="display: inline-block; margin-top: 1.5rem; color: var(--primary); font-weight: 700; text-decoration: none;">Kembali ke Dashboard</a>
    </div>
@endif
@endsection