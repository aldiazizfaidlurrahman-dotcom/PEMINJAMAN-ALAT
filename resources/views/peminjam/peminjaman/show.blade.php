@extends('peminjam.layout')

@section('title', 'Detail Peminjaman')

@section('styles')
<style>
    /* Status Card Styling */
    .status-banner {
        background: white;
        border-radius: 20px;
        padding: 1.5rem 2rem;
        border: 1px solid #e2e8f0;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .status-icon-box {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Info Sections Grid */
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .info-card {
        background: white;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        padding: 1.5rem;
    }

    .info-card h4 {
        font-size: 0.9rem;
        font-weight: 800;
        color: var(--primary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .info-row:last-child { border-bottom: none; }

    .info-row label {
        font-size: 0.875rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    .info-row span {
        font-size: 0.875rem;
        color: var(--text-main);
        font-weight: 700;
        text-align: right;
    }

    /* Status Colors */
    .status-menunggu-bg { background: #fffbeb; color: #d97706; }
    .status-disetujui-bg { background: #ecfdf5; color: #059669; }
    .status-ditolak-bg { background: #fef2f2; color: #e11d48; }
    .status-dikembalikan-bg { background: #eff6ff; color: #2563eb; }

    .text-menunggu { color: #d97706; }
    .text-disetujui { color: #059669; }
    .text-ditolak { color: #e11d48; }
    .text-dikembalikan { color: #2563eb; }

    /* Action Buttons */
    .action-bar {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        padding-top: 1rem;
    }

    .btn-action {
        padding: 12px 24px;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 700;
        text-decoration: none;
        transition: 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-cancel { background: #fef2f2; color: #e11d48; }
    .btn-cancel:hover { background: #fee2e2; }

    .btn-back { background: #f1f5f9; color: #475569; }
    .btn-back:hover { background: #e2e8f0; }

    @media (max-width: 768px) {
        .detail-grid { grid-template-columns: 1fr; }
        .status-banner { flex-direction: column; text-align: center; }
    }
</style>
@endsection

@section('content')
<div style="margin-bottom: 2rem;">
    <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; letter-spacing: -0.025em;">Detail Peminjaman</h2>
    <p style="color: var(--text-muted); font-size: 0.9rem;">Informasi lengkap mengenai status dan rincian peminjaman alat Anda.</p>
</div>

{{-- Status Banner --}}
<div class="status-banner">
    <div class="status-icon-box status-{{ $peminjaman->status }}-bg">
        @switch($peminjaman->status)
            @case('menunggu')
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                @break
            @case('disetujui')
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                @break
            @case('ditolak')
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                @break
            @case('dikembalikan')
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 17 4 12 9 7"/><path d="M20 18v-2a4 4 0 0 0-4-4H4"/></svg>
                @break
        @endswitch
    </div>
    <div class="status-info">
        <span style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Status Saat Ini</span>
        <h3 class="text-{{ $peminjaman->status }}" style="font-size: 1.25rem; font-weight: 800;">
            {{ ucfirst(match($peminjaman->status) {
                'menunggu' => 'Menunggu Persetujuan Petugas',
                'disetujui' => 'Permohonan Disetujui',
                'ditolak' => 'Permohonan Ditolak',
                'dikembalikan' => 'Alat Telah Dikembalikan',
                default => $peminjaman->status
            }) }}
        </h3>
    </div>
</div>

<div class="detail-grid">
    {{-- Alat Info --}}
    <div class="info-card">
        <h4><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg> Informasi Alat</h4>
        <div class="info-row">
            <label>Nama Alat</label>
            <span>{{ $peminjaman->alat->nama_alat }}</span>
        </div>
        <div class="info-row">
            <label>Kategori</label>
            <span>{{ $peminjaman->alat->kategori->nama_kategori }}</span>
        </div>
        <div class="info-row">
            <label>Kondisi Alat</label>
            <span class="text-{{ $peminjaman->alat->kondisi === 'baik' ? 'disetujui' : 'ditolak' }}">
                {{ ucfirst($peminjaman->alat->kondisi) }}
            </span>
        </div>
    </div>

    {{-- Peminjaman Info --}}
    <div class="info-card">
        <h4><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> Jadwal Pinjam</h4>
        <div class="info-row">
            <label>Tanggal Pinjam</label>
            <span>{{ $peminjaman->tanggal_pinjam->format('d F Y') }}</span>
        </div>
        <div class="info-row">
            <label>Batas Kembali</label>
            <span>{{ $peminjaman->tanggal_kembali->format('d F Y') }}</span>
        </div>
        <div class="info-row">
            <label>Durasi</label>
            <span>{{ $peminjaman->tanggal_kembali->diffInDays($peminjaman->tanggal_pinjam) + 1 }} Hari</span>
        </div>
    </div>
</div>

<div class="info-card" style="margin-bottom: 2rem;">
    <h4><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg> Informasi Tambahan</h4>
    <div class="info-row">
        <label>Tanggal Diajukan</label>
        <span>{{ $peminjaman->created_at->format('d M Y, H:i') }}</span>
    </div>
    @if($peminjaman->status === 'ditolak')
    <div class="info-row" style="border-bottom: none; background: #fff1f2; padding: 1rem; border-radius: 12px; margin-top: 10px;">
        <label style="color: #e11d48;">Alasan Penolakan</label>
        <span style="color: #e11d48; text-align: left; flex: 1; padding-left: 2rem;">{{ $peminjaman->alasan_penolakan ?? 'Tidak ada alasan spesifik.' }}</span>
    </div>
    @endif
</div>

{{-- Actions --}}
<div class="action-bar">
    <a href="{{ route('peminjam.peminjaman.index') }}" class="btn-action btn-back">Kembali ke Riwayat</a>
    
    @if ($peminjaman->status === 'menunggu')
        <form action="{{ route('peminjam.peminjaman.cancel', $peminjaman->id) }}" method="POST" onsubmit="return confirm('Batalkan permohonan peminjaman ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-action btn-cancel">Batalkan Permohonan</button>
        </form>
    @endif
</div>
@endsection