@extends('peminjam.layout')

@section('title', 'Ajukan Peminjaman')

@section('styles')
<style>
    /* Form Layout */
    .form-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1.2fr;
        gap: 2rem;
        align-items: start;
    }

    /* Alat Preview Card */
    .alat-preview-card {
        background: white;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        padding: 1.5rem;
        position: sticky;
        top: 2rem;
    }

    .alat-preview-card h4 {
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

    .preview-row {
        display: flex;
        flex-direction: column;
        gap: 4px;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .preview-row:last-child { border-bottom: none; }
    .preview-row label { font-size: 0.75rem; color: var(--text-muted); font-weight: 600; }
    .preview-row span { font-size: 0.95rem; color: var(--text-main); font-weight: 700; }

    /* Form Styling */
    .booking-card {
        background: white;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        padding: 2rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
    }

    .form-group { margin-bottom: 1.5rem; }
    .form-group label {
        display: block;
        font-size: 0.875rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 8px;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid #cbd5e1;
        font-family: inherit;
        font-size: 0.9rem;
        transition: 0.2s;
    }
    .form-control:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    /* Info Duration */
    .duration-box {
        background: var(--primary-light);
        border: 1px dashed var(--primary);
        border-radius: 12px;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 1.5rem;
        color: var(--primary-dark);
    }

    .btn-submit {
        width: 100%;
        padding: 14px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: 0.2s;
        margin-top: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .btn-submit:hover { background: var(--primary-dark); transform: translateY(-2px); }

    .badge-pill {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    @media (max-width: 768px) {
        .form-grid { grid-template-columns: 1fr; }
        .alat-preview-card { position: static; }
    }
</style>
@endsection

@section('content')
<div class="form-container">
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('peminjam.alat.index') }}" style="text-decoration: none; color: var(--primary); font-weight: 700; font-size: 0.875rem; display: flex; align-items: center; gap: 4px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Kembali ke Katalog
        </a>
        <h2 style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin-top: 0.5rem;">Ajukan Peminjaman</h2>
    </div>

    @if ($errors->any())
        <div style="padding: 1rem; background: #fef2f2; border: 1px solid #fee2e2; border-radius: 12px; color: #e11d48; margin-bottom: 1.5rem; font-size: 0.875rem;">
            <strong style="display: block; margin-bottom: 4px;">Mohon periksa kembali:</strong>
            <ul style="margin-left: 1.25rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-grid">
        {{-- Preview Samping --}}
        <div class="alat-preview-card">
            <h4><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg> Alat Terpilih</h4>
            <div class="preview-row">
                <label>Nama Peralatan</label>
                <span>{{ $alat->nama_alat }}</span>
            </div>
            <div class="preview-row">
                <label>Kategori</label>
                <span>{{ $alat->kategori->nama_kategori }}</span>
            </div>
            <div class="preview-row">
                <label>Kondisi & Stok</label>
                <div style="display: flex; gap: 8px; margin-top: 4px;">
                    <span class="badge-pill" style="background: #ecfdf5; color: #059669;">{{ ucfirst($alat->kondisi) }}</span>
                    <span class="badge-pill" style="background: #eff6ff; color: #2563eb;">{{ $alat->stok }} Unit Ready</span>
                </div>
            </div>
        </div>

        {{-- Form Input --}}
        <div class="booking-card">
            <form action="{{ route('peminjam.alat.store', $alat->id) }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="tanggal_pinjam">Rencana Pengambilan</label>
                    <input type="date" id="tanggal_pinjam" name="tanggal_pinjam" 
                           class="form-control" 
                           min="{{ now()->format('Y-m-d') }}"
                           value="{{ old('tanggal_pinjam', now()->format('Y-m-d')) }}" required>
                </div>

                <div class="form-group">
                    <label for="tanggal_kembali">Rencana Pengembalian</label>
                    <input type="date" id="tanggal_kembali" name="tanggal_kembali" 
                           class="form-control" 
                           min="{{ now()->format('Y-m-d') }}"
                           value="{{ old('tanggal_kembali') }}" required>
                </div>

                <div class="duration-box" id="duration-container" style="display: none;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span id="duration-text" style="font-weight: 700; font-size: 0.9rem;"></span>
                </div>

                <button type="submit" class="btn-submit">
                    Ajukan Sekarang
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </button>
                
                <p style="text-align: center; color: var(--text-muted); font-size: 0.75rem; margin-top: 1rem;">
                    * Permohonan Anda akan ditinjau oleh petugas dalam waktu 1x24 jam.
                </p>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tglPinjam = document.getElementById('tanggal_pinjam');
        const tglKembali = document.getElementById('tanggal_kembali');
        const durBox = document.getElementById('duration-container');
        const durText = document.getElementById('duration-text');

        function calculate() {
            if (tglPinjam.value && tglKembali.value) {
                const start = new Date(tglPinjam.value);
                const end = new Date(tglKembali.value);
                
                // Set minimal tanggal kembali adalah tanggal pinjam
                tglKembali.min = tglPinjam.value;

                if (end >= start) {
                    const diffTime = Math.abs(end - start);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                    
                    durText.textContent = `Estimasi durasi: ${diffDays} Hari`;
                    durBox.style.display = 'flex';
                } else {
                    durBox.style.display = 'none';
                }
            }
        }

        tglPinjam.addEventListener('change', calculate);
        tglKembali.addEventListener('change', calculate);
        calculate(); // Run on load if values exist
    });
</script>
@endsection