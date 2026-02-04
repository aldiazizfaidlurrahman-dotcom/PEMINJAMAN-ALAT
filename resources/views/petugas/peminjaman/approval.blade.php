@extends('petugas.layout')

@section('title', 'Persetujuan Peminjaman')

@section('styles')
<style>
    /* Info Card Styling */
    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
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
        font-weight: 500;
    }

    /* Condition Badges */
    .stok-status {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        margin-top: 8px;
    }
    .status-ready { background: #ecfdf5; color: #059669; }
    .status-empty { background: #fef2f2; color: #ef4444; }

    /* Action Footer */
    .action-footer {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #e2e8f0;
    }

    .btn-action {
        flex: 1;
        padding: 14px;
        border-radius: 12px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: 0.2s;
        text-align: center;
        font-size: 0.9rem;
    }

    .btn-approve { background: var(--primary); color: white; }
    .btn-approve:hover { background: #047857; transform: translateY(-2px); }
    .btn-approve:disabled { background: #cbd5e1; cursor: not-allowed; transform: none; }

    .btn-reject { background: #fef2f2; color: #ef4444; border: 1px solid #fee2e2; }
    .btn-reject:hover { background: #fee2e2; }

    /* Modal Styling */
    .modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);
        display: none; align-items: center; justify-content: center; z-index: 2000;
    }
    .modal-content {
        background: white; padding: 2rem; border-radius: 20px;
        width: 100%; max-width: 450px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
    }
    textarea {
        width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px;
        margin: 1rem 0; font-family: inherit; resize: none;
    }
</style>
@endsection

@section('content')
<div style="margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 800;">Detail Permintaan</h2>
        <p style="color: var(--text-muted);">Tinjau informasi peminjam sebelum memberikan keputusan.</p>
    </div>
    <a href="{{ route('petugas.peminjaman.menunggu') }}" style="text-decoration: none; color: var(--text-muted); font-weight: 600; font-size: 0.875rem;">
        ← Kembali
    </a>
</div>

<div class="card">
    <div class="section-title">
        <span style="background: var(--primary-light); padding: 8px; border-radius: 10px;">👤</span> 
        Data Peminjam
    </div>
    <div class="info-grid">
        <div class="info-item">
            <label>Nama Lengkap</label>
            <p>{{ $peminjaman->pengguna->nama }}</p>
        </div>
        <div class="info-item">
            <label>Username / ID</label>
            <p>{{ $peminjaman->pengguna->username }}</p>
        </div>
        <div class="info-item">
            <label>Email Instansi</label>
            <p>{{ $peminjaman->pengguna->email }}</p>
        </div>
    </div>

    <div class="section-title" style="margin-top: 3rem;">
        <span style="background: #eff6ff; padding: 8px; border-radius: 10px;">📦</span> 
        Informasi Alat
    </div>
    <div class="info-grid">
        <div class="info-item">
            <label>Nama Alat</label>
            <p>{{ $peminjaman->alat->nama_alat }}</p>
        </div>
        <div class="info-item">
            <label>Kategori</label>
            <p>{{ $peminjaman->alat->kategori->nama_kategori }}</p>
        </div>
        <div class="info-item">
            <label>Ketersediaan Stok</label>
            <p>{{ $peminjaman->alat->stok }} Unit</p>
            @if($peminjaman->alat->stok > 0)
                <span class="stok-status status-ready">✓ Stok Tersedia</span>
            @else
                <span class="stok-status status-empty">⚠ Stok Habis</span>
            @endif
        </div>
        <div class="info-item">
            <label>Durasi Pinjam</label>
            <p>{{ $peminjaman->tanggal_pinjam->format('d M') }} - {{ $peminjaman->tanggal_kembali->format('d M Y') }}</p>
            <small style="color: var(--primary); font-weight: 700;">({{ $peminjaman->tanggal_pinjam->diffInDays($peminjaman->tanggal_kembali) + 1 }} Hari)</small>
        </div>
    </div>

    @if($peminjaman->alat->stok <= 0)
        <div style="margin-top: 2rem; padding: 1rem; background: #fff1f2; border-radius: 12px; color: #e11d48; font-size: 0.875rem; font-weight: 600; display: flex; align-items: center; gap: 10px;">
            <span>🚫</span> Alat ini tidak dapat dipinjam karena stok kosong.
        </div>
    @endif

    <div class="action-footer">
        <button type="button" class="btn-action btn-reject" onclick="toggleModal(true)">
            Tolak Permintaan
        </button>
        
        <form action="{{ route('petugas.peminjaman.approve', $peminjaman->id) }}" method="POST" style="flex:1;">
            @csrf
            <button type="submit" class="btn-action btn-approve" {{ $peminjaman->alat->stok <= 0 ? 'disabled' : '' }}>
                Setujui Peminjaman
            </button>
        </form>
    </div>
</div>

{{-- Modal Penolakan --}}
<div class="modal-overlay" id="rejectModal">
    <div class="modal-content">
        <h3 style="font-weight: 800; color: #e11d48;">Tolak Peminjaman</h3>
        <p style="font-size: 0.875rem; color: var(--text-muted); margin-top: 4px;">Berikan alasan mengapa permintaan ini ditolak.</p>
        
        <form action="{{ route('petugas.peminjaman.reject', $peminjaman->id) }}" method="POST">
            @csrf
            <textarea name="alasan" rows="4" placeholder="Contoh: Alat sedang dalam pemeliharaan rutin..." required></textarea>
            
            <div style="display: flex; gap: 10px;">
                <button type="button" class="btn-action btn-reject" style="flex:1;" onclick="toggleModal(false)">Batal</button>
                <button type="submit" class="btn-action btn-approve" style="flex:2; background: #e11d48;">Konfirmasi Tolak</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function toggleModal(show) {
        document.getElementById('rejectModal').style.display = show ? 'flex' : 'none';
    }

    // Menutup modal jika klik di area luar modal
    window.onclick = function(event) {
        let modal = document.getElementById('rejectModal');
        if (event.target == modal) {
            toggleModal(false);
        }
    }
</script>
@endsection