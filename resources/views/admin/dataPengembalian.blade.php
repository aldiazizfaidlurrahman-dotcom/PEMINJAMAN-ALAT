@extends('admin.layout')
@section('title', 'Histori Pengembalian')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    /* 1. Global Optimization */
    .main-container { 
        font-family: 'Inter', sans-serif; 
        color: #1e293b; 
        background-color: #f8fafc;
        min-height: 100vh;
        width: 100%;
        padding: 2rem;
    }

    /* 2. Enhanced Typography */
    .heading-title { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; letter-spacing: -0.04em; color: #0f172a; }
    .text-primary-data { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; color: #1e293b; font-size: 0.95rem; }
    .text-secondary-data { font-family: 'Inter', sans-serif; font-weight: 500; color: #64748b; font-size: 0.8rem; }
    
    /* 3. Slim Stat Cards */
    .stat-grid { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); 
        gap: 1.25rem; 
        margin-bottom: 2rem; 
    }
    .stat-card-slim {
        background: white; border-radius: 16px; padding: 1.25rem; border: 1px solid #e2e8f0;
        display: flex; align-items: center; gap: 1rem; transition: all 0.3s ease;
    }
    .stat-card-slim:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.04); }
    .icon-box-sm { 
        width: 42px; height: 42px; border-radius: 10px; 
        display: flex; align-items: center; justify-content: center; font-size: 1.1rem; 
    }

    /* 4. Full-Width Filter & Search */
    .filter-action-bar {
        background: white; border-radius: 16px; padding: 1rem; border: 1px solid #e2e8f0;
        margin-bottom: 1.5rem; display: flex; align-items: center; gap: 1rem;
    }
    .search-wrapper { position: relative; flex-grow: 1; }
    .search-wrapper i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #6366f1; }
    .input-modern {
        width: 100%; height: 46px; padding-left: 45px; background: #f8fafc;
        border: 1px solid #e2e8f0; border-radius: 12px; font-size: 0.9rem; font-weight: 500; outline: none; transition: 0.2s;
    }
    .input-modern:focus { border-color: #2563eb; background: white; box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1); }

    /* 5. Luxury Full-Width Table */
    .table-container-full { 
        background: white; border-radius: 20px; border: 1px solid #e2e8f0; 
        overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); 
    }
    .modern-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .modern-table thead th {
        background: #f8fafc; color: #94a3b8; font-size: 0.7rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: 0.1em; padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9;
    }
    .modern-table tbody td { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f8fafc; vertical-align: middle; }
    .modern-table tbody tr:hover { background-color: #fcfdfe; }

    /* 6. Timeline & Status Styling */
    .timeline-item { display: flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 600; color: #475569; }
    .timeline-label { font-size: 0.65rem; font-weight: 800; color: #cbd5e1; text-transform: uppercase; width: 50px; }
    
    .st-pill {
        display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px;
        border-radius: 8px; font-size: 0.75rem; font-weight: 700;
    }
    .pill-green { background: #ecfdf5; color: #059669; }
    .pill-red { background: #fff1f2; color: #e11d48; }
    .dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }

    /* 7. Action Buttons */
    .btn-lux {
        height: 46px; padding: 0 1.5rem; border-radius: 12px; font-weight: 700;
        display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; font-size: 0.85rem;
    }
    .btn-lux-primary { background: #2563eb; color: white; border: none; }
    .btn-lux-primary:hover { background: #1d4ed8; transform: translateY(-2px); box-shadow: 0 8px 15px rgba(37, 99, 235, 0.2); color: white; }
</style>

<div class="main-container">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <span class="text-primary fw-800" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em;">Audit Trail</span>
            <h2 class="heading-title mb-0">Histori Pengembalian</h2>
        </div>
        <div class="text-end d-none d-md-block">
            <div class="text-secondary-data fw-700">{{ now()->translatedFormat('l, d F Y') }}</div>
            <div class="heading-title fs-5" style="color: #2563eb;">{{ now()->format('H:i') }} <span class="fs-6 opacity-50">WIB</span></div>
        </div>
    </div>

    <div class="stat-grid">
        <div class="stat-card-slim">
            <div class="icon-box-sm" style="background: #eef2ff; color: #4f46e5;"><i class="bi bi-folder2-open"></i></div>
            <div>
                <div class="text-secondary-data fw-800 uppercase" style="font-size: 0.65rem;">Total Log</div>
                <div class="text-primary-data fs-5">{{ $pengembalian->total() }}</div>
            </div>
        </div>
        <div class="stat-card-slim">
            <div class="icon-box-sm" style="background: #ecfdf5; color: #10b981;"><i class="bi bi-patch-check"></i></div>
            <div>
                <div class="text-secondary-data fw-800 uppercase" style="font-size: 0.65rem;">Tepat Waktu</div>
                <div class="text-primary-data fs-5 text-success">{{ $pengembalian->where('hari_keterlambatan', 0)->count() }}</div>
            </div>
        </div>
        <div class="stat-card-slim">
            <div class="icon-box-sm" style="background: #fef2f2; color: #ef4444;"><i class="bi bi-exclamation-octagon"></i></div>
            <div>
                <div class="text-secondary-data fw-800 uppercase" style="font-size: 0.65rem;">Terlambat</div>
                <div class="text-primary-data fs-5 text-danger">{{ $pengembalian->where('hari_keterlambatan', '>', 0)->count() }}</div>
            </div>
        </div>
        <div class="stat-card-slim">
            <div class="icon-box-sm" style="background: #fffbeb; color: #d97706;"><i class="bi bi-wallet2"></i></div>
            <div>
                <div class="text-secondary-data fw-800 uppercase" style="font-size: 0.65rem;">Total Denda</div>
                <div class="text-primary-data fs-5 text-warning">Rp{{ number_format($pengembalian->sum('denda'), 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <div class="filter-action-bar">
        <div class="search-wrapper">
            <i class="bi bi-search"></i>
            <input type="text" id="liveSearch" class="input-modern" placeholder="Cari peminjam, username, atau nama alat secara instan...">
        </div>
        <button class="btn-lux btn-lux-primary"><i class="bi bi-filter"></i> Filter</button>
        <a href="{{ request()->url() }}" class="btn-lux btn-light border" style="background: white;"><i class="bi bi-arrow-clockwise"></i> Reset</a>
    </div>

    <div class="table-container-full">
        <div class="table-responsive">
            <table class="modern-table" id="returnTable">
                <thead>
                    <tr>
                        <th class="ps-4">No.</th>
                        <th>Identitas Peminjam</th>
                        <th>Detail Alat</th>
                        <th>Timeline Kembali</th>
                        <th class="text-center">Status</th>
                        <th class="text-end pe-4">Nominal Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengembalian as $p)
                    <tr class="data-row">
                        <td class="ps-4 fw-800 text-muted" style="width: 50px; font-size: 0.8rem;">#{{ $loop->iteration }}</td>
                        <td class="search-peminjam">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary text-white fw-800 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; border-radius: 10px; font-size: 1rem;">
                                    {{ substr($p->pengguna->nama, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-primary-data">{{ $p->pengguna->nama }}</div>
                                    <div class="text-secondary-data">@ {{ $p->pengguna->username }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="search-alat">
                            <div class="text-primary-data">{{ $p->alat->nama_alat }}</div>
                            <span class="text-secondary-data fw-700 text-primary uppercase" style="font-size: 0.65rem;">{{ $p->alat->kategori->nama_kategori }}</span>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                <div class="timeline-item"><span class="timeline-label">Input</span> <span>{{ $p->tanggal_pinjam }}</span></div>
                                <div class="timeline-item text-success"><span class="timeline-label text-success">Selesai</span> <span>{{ $p->tanggal_dikembalikan }}</span></div>
                            </div>
                        </td>
                        <td class="text-center">
                            @if($p->hari_keterlambatan > 0)
                                <div class="st-pill pill-red"><span class="dot"></span> {{ $p->hari_keterlambatan }} HARI</div>
                            @else
                                <div class="st-pill pill-green"><span class="dot"></span> ON TIME</div>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            @if($p->denda > 0)
                                <div class="text-primary-data text-danger fs-6">Rp{{ number_format($p->denda, 0, ',', '.') }}</div>
                            @else
                                <span class="text-secondary-data fw-800 opacity-25">---</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted fw-600">
                            <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                            Tidak ada data histori ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 bg-white border-top d-flex justify-content-between align-items-center">
            <div class="text-secondary-data fw-800 uppercase">
                Menampilkan {{ $pengembalian->firstItem() ?? 0 }} - {{ $pengembalian->lastItem() ?? 0 }} dari {{ $pengembalian->total() }} Data
            </div>
            <div>
                {{ $pengembalian->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('liveSearch').addEventListener('input', function() {
        let filter = this.value.toLowerCase();
        document.querySelectorAll('.data-row').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(filter) ? "" : "none";
        });
    });
</script>
@endsection