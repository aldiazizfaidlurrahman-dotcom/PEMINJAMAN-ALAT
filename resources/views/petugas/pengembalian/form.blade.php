@extends('petugas.layout')

@section('title', 'Proses Pengembalian')

@section('styles')
<style>
    /* Info Grid Styling */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .info-item label {
        display: block;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .info-item p {
        font-size: 1rem;
        color: var(--text-main);
        font-weight: 600;
    }

    /* Penalty Box */
    .penalty-card {
        background: #fff8e1;
        border: 1px solid #ffe082;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .penalty-icon {
        font-size: 2.5rem;
        background: white;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }

    .penalty-amount {
        font-size: 1.25rem;
        font-weight: 800;
        color: #d97706;
    }

    /* Custom Radio Condition */
    .condition-group {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-top: 0.5rem;
    }

    .condition-item input[type="radio"] {
        display: none;
    }

    .condition-label {
        display: block;
        text-align: center;
        padding: 1rem;
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        cursor: pointer;
        font-weight: 700;
        font-size: 0.875rem;
        transition: all 0.2s;
        color: var(--text-muted);
    }

    .condition-item input[type="radio"]:checked + .condition-label.baik { border-color: #10b981; background: #ecfdf5; color: #065f46; }
    .condition-item input[type="radio"]:checked + .condition-label.rusak { border-color: #f59e0b; background: #fffbeb; color: #92400e; }
    .condition-item input[type="radio"]:checked + .condition-label.hilang { border-color: #ef4444; background: #fef2f2; color: #991b1b; }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        font-family: inherit;
    }

    .btn-submit {
        width: 100%;
        padding: 14px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: 0.2s;
        margin-top: 1rem;
    }
    .btn-submit:hover { background: #047857; transform: translateY(-2px); }
</style>
@endsection

@section('content')
<div style="margin-bottom: 2rem;">
    <h2 style="font-size: 1.5rem; font-weight: 800;">Konfirmasi Pengembalian</h2>
    <p style="color: var(--text-muted);">Selesaikan transaksi peminjaman dengan memeriksa kondisi alat.</p>
</div>

@php
    $keterlambatan = $peminjaman->calculateKeterlambatan();
    $dendaValue = $denda ?? 0;
@endphp

<div class="card">
    <div style="font-weight: 800; font-size: 1rem; margin-bottom: 1.5rem; color: var(--primary); display: flex; align-items: center; gap: 8px;">
        <span>📋</span> Detail Peminjaman
    </div>

    <div class="info-grid">
        <div class="info-item">
            <label>Nama Peminjam</label>
            <p>{{ $peminjaman->pengguna->nama }}</p>
        </div>
        <div class="info-item">
            <label>Alat yang Dipinjam</label>
            <p>{{ $peminjaman->alat->nama_alat }}</p>
        </div>
        <div class="info-item">
            <label>Tanggal Pinjam</label>
            <p>{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</p>
        </div>
        <div class="info-item">
            <label>Batas Kembali</label>
            <p>{{ $peminjaman->tanggal_kembali->format('d M Y') }}</p>
        </div>
    </div>

    @if ($keterlambatan > 0)
        <div class="penalty-card">
            <div class="penalty-icon">⏰</div>
            <div>
                <div style="color: #92400e; font-size: 0.875rem; font-weight: 700; text-transform: uppercase;">Terlambat {{ $keterlambatan }} Hari</div>
                <div class="penalty-amount">Total Denda: Rp {{ number_format($dendaValue, 0, ',', '.') }}</div>
            </div>
        </div>
    @else
        <div style="margin-bottom: 2rem; padding: 1rem; background: #ecfdf5; border-radius: 12px; color: #059669; font-weight: 600; display: flex; align-items: center; gap: 10px;">
            <span>✅</span> Pengembalian tepat waktu. Tidak ada denda.
        </div>
    @endif

    <form method="POST" action="{{ route('petugas.pengembalian.process', $peminjaman->id) }}">
        @csrf
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-weight: 700; font-size: 0.875rem; margin-bottom: 8px;">Tanggal Dikembalikan</label>
            <input type="date" name="tanggal_dikembalikan" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
        </div>

        <div style="margin-bottom: 2rem;">
            <label style="display: block; font-weight: 700; font-size: 0.875rem; margin-bottom: 8px;">Kondisi Alat Saat Ini</label>
            <div class="condition-group">
                <div class="condition-item">
                    <input type="radio" id="cond_baik" name="kondisi_alat" value="baik" checked>
                    <label for="cond_baik" class="condition-label baik">✓ Baik</label>
                </div>
                <div class="condition-item">
                    <input type="radio" id="cond_rusak" name="kondisi_alat" value="rusak">
                    <label for="cond_rusak" class="condition-label rusak">⚠️ Rusak</label>
                </div>
                <div class="condition-item">
                    <input type="radio" id="cond_hilang" name="kondisi_alat" value="hilang">
                    <label for="cond_hilang" class="condition-label hilang">❌ Hilang</label>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 1rem;">
            <a href="{{ route('petugas.pengembalian.index') }}" style="flex: 1; text-align: center; padding: 14px; border-radius: 12px; border: 1px solid #e2e8f0; text-decoration: none; color: var(--text-muted); font-weight: 700; font-size: 0.9rem;">Batal</a>
            <button type="submit" class="btn-submit" style="flex: 2; margin-top: 0;">Konfirmasi Pengembalian Alat</button>
        </div>
    </form>
</div>
@endsection