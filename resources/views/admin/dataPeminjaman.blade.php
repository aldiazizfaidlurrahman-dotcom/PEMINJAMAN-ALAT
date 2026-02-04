@extends('admin.layout')
@section('title', 'Histori Peminjaman')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
    .fw-800 { font-weight: 800; }
    .header-icon-box { width: 50px; height: 50px; background: #2563eb; color: white; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);}
    .stat-card { background: white; border-radius: 20px; padding: 1.5rem; display: flex; align-items: center; border: 1px solid #f1f5f9; transition: all 0.3s ease;}
    .stat-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; margin-right: 15px;}
    .bg-soft-blue { background: #f0f7ff; color: #2563eb; }
    .bg-soft-green { background: #f0fdf4; color: #16a34a; }
    .bg-soft-purple { background: #faf5ff; color: #9333ea; }
    .filter-card { background: white; border-radius: 20px; padding: 20px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px rgba(0,0,0,0.02);}
    .input-custom { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 10px 15px; font-size: 0.9rem;}
    .main-table-card { background: white; border-radius: 24px; border: 1px solid #f1f5f9; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.02);}
    .table thead th { background: #f8fafc; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; padding: 20px;}
    .status-badge { padding: 6px 14px; border-radius: 50px; font-size: 0.75rem; font-weight: 800; display: inline-flex; align-items: center; gap: 8px;}
    .dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; }
    .st-returned { background: #f1f5f9; color: #64748b; }
    .st-returned .dot { background: #64748b; }
</style>

<div class="container-fluid py-4 animate__animated animate__fadeIn">
    <div class="row align-items-center mb-5">
        <div class="col-md-6 d-flex align-items-center">
            <div class="header-icon-box me-3"><i class="bi bi-clock-history"></i></div>
            <div>
                <h2 class="fw-800 text-dark mb-0" style="letter-spacing: -1.5px;">Histori Peminjaman</h2>
                <p class="text-muted small mb-0">Audit & Management Console</p>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon bg-soft-blue"><i class="bi bi-hourglass-split"></i></div>
                <div>
                    <div class="text-muted small fw-bold text-uppercase">Pending</div>
                    <h3 class="fw-800 mb-0">{{ $peminjaman->where('status', 'menunggu')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon bg-soft-green"><i class="bi bi-check-circle"></i></div>
                <div>
                    <div class="text-muted small fw-bold text-uppercase">Disetujui</div>
                    <h3 class="fw-800 mb-0">{{ $peminjaman->where('status', 'disetujui')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon bg-soft-purple"><i class="bi bi-arrow-return-left"></i></div>
                <div>
                    <div class="text-muted small fw-bold text-uppercase">Selesai</div>
                    <h3 class="fw-800 mb-0">{{ $peminjaman->where('status', 'dikembalikan')->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="filter-card mb-5">
        <form method="GET" class="row g-3 align-items-center" id="liveSearchForm" autocomplete="off">
            <div class="col-lg-6">
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" value="{{ $search }}" class="form-control border-start-0 input-custom" placeholder="Cari peminjam, alat, atau kategori..." id="liveSearchInput" autocomplete="off">
                </div>
            </div>
            <div class="col-lg-3">
                <select name="status" class="form-select input-custom fw-bold" id="statusFilter">
                    <option value="">Semua Status</option>
                    <option value="menunggu" {{ $status == 'menunggu' ? 'selected' : '' }}>⏳ Menunggu</option>
                    <option value="disetujui" {{ $status == 'disetujui' ? 'selected' : '' }}>✅ Disetujui</option>
                    <option value="ditolak" {{ $status == 'ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                    <option value="dikembalikan" {{ $status == 'dikembalikan' ? 'selected' : '' }}>🔄 Dikembalikan</option>
                </select>
            </div>
        </form>
    </div>

    <div class="main-table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Peminjam</th>
                        <th>Alat</th>
                        <th>Kategori</th>
                        <th>Tanggal Pinjam</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($peminjaman as $p)
                    <tr>
                        <td class="ps-4 fw-bold text-muted">{{ ($peminjaman->currentPage()-1)*$peminjaman->perPage() + $loop->iteration }}</td>
                        <td>{{ $p->pengguna->nama }}</td>
                        <td>{{ $p->alat->nama_alat }}</td>
                        <td>{{ $p->alat->kategori->nama_kategori }}</td>
                        <td>{{ $p->tanggal_pinjam }}</td>
                        <td class="text-center">
                            <div class="status-badge st-returned">
                                <span class="dot"></span> {{ strtoupper($p->status) }}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-5">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div style="margin-top:1rem;">
        {{ $peminjaman->appends(request()->except('page'))->links() }}
    </div>
</div>

<script>
// Live search & filter
document.addEventListener('DOMContentLoaded', function() {
    let typingTimer;
    const searchInput = document.getElementById('liveSearchInput');
    const statusFilter = document.getElementById('statusFilter');
    const form = document.getElementById('liveSearchForm');

    searchInput.addEventListener('input', function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {
            form.submit();
        }, 450);
    });

    statusFilter.addEventListener('change', function() {
        form.submit();
    });
});
</script>
@endsection