@extends('admin.layout')
@section('title', 'Log Aktivitas Sistem')
@section('content')

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<style>
    /* Global Reset untuk Dashboard Modern */
    .main-content-wrapper {
        font-family: 'Inter', sans-serif;
        background-color: #f9fafb;
        min-height: 100vh;
        color: #1f2937;
    }

    /* Header Styling */
    .page-header {
        margin-bottom: 2rem;
    }
    .page-title {
        font-weight: 700;
        letter-spacing: -0.025em;
        color: #111827;
    }

    /* Filter Card - Modern Floating Look */
    .filter-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    .form-control, .form-select {
        border: 1px solid #d1d5db;
        border-radius: 10px;
        padding: 0.625rem 0.875rem;
        font-size: 0.875rem;
        transition: all 0.2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }
    .btn-search {
        background-color: #4f46e5;
        border: none;
        border-radius: 10px;
        padding: 0.625rem 1.25rem;
        font-weight: 600;
        transition: all 0.2s;
    }
    .btn-search:hover {
        background-color: #4338ca;
        transform: translateY(-1px);
    }

    /* Table Styling */
    .table-container {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
    }
    .table thead th {
        background-color: #f8fafc;
        padding: 1rem 1.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #64748b;
        border-bottom: 1px solid #e2e8f0;
    }
    .table tbody td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
    }
    .table tbody tr:hover {
        background-color: #fbfcfe;
    }

    /* Soft Badges */
    .badge-soft {
        padding: 0.35em 0.65em;
        font-weight: 600;
        border-radius: 6px;
        font-size: 0.75rem;
    }
    .bg-soft-danger { background-color: #fef2f2; color: #b91c1c; }
    .bg-soft-info { background-color: #f0f9ff; color: #0369a1; }
    .bg-soft-success { background-color: #f0fdf4; color: #15803d; }
    .bg-soft-warning { background-color: #fffbeb; color: #b45309; }
    .bg-soft-primary { background-color: #eef2ff; color: #4338ca; }

    /* Activity Icon Wrapper */
    .icon-box {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        font-size: 1.1rem;
    }
</style>

<div class="main-content-wrapper py-5 px-4">
    <div class="container-fluid">
        <div class="page-header d-md-flex align-items-center justify-content-between text-center text-md-start">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item active fw-medium">Log Aktivitas</li>
                    </ol>
                </nav>
                <h2 class="page-title mb-0"><i class="bi bi-shield-check text-primary me-2"></i>Log Aktivitas Sistem</h2>
                <p class="text-muted mt-1">Audit trail lengkap aktivitas seluruh pengguna sistem.</p>
            </div>
            <div class="mt-3 mt-md-0">
                <button class="btn btn-outline-secondary btn-sm rounded-pill px-3 me-2">
                    <i class="bi bi-download me-1"></i> Export Log
                </button>
            </div>
        </div>

        <div class="filter-card">
            <form action="" method="GET" class="row g-3">
                <div class="col-lg-4 col-md-6">
                    <label class="form-label small fw-bold text-muted">Pencarian Cepat</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control border-start-0" placeholder="Nama, detail, atau aktivitas...">
                    </div>
                </div>
                <div class="col-lg-2 col-md-3">
                    <label class="form-label small fw-bold text-muted">Filter Role</label>
                    <select name="role" class="form-select">
                        <option value="">Semua Role</option>
                        <option value="admin" {{ request('role')=='admin'?'selected':'' }}>Admin</option>
                        <option value="petugas" {{ request('role')=='petugas'?'selected':'' }}>Petugas</option>
                        <option value="peminjam" {{ request('role')=='peminjam'?'selected':'' }}>Peminjam</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-3">
                    <label class="form-label small fw-bold text-muted">Tipe Aktivitas</label>
                    <select name="jenis" class="form-select">
                        <option value="">Semua Jenis</option>
                        <option value="login" {{ request('jenis')=='login'?'selected':'' }}>Login</option>
                        <option value="logout" {{ request('jenis')=='logout'?'selected':'' }}>Logout</option>
                        <option value="tambah" {{ request('jenis')=='tambah'?'selected':'' }}>Tambah</option>
                        <option value="edit" {{ request('jenis')=='edit'?'selected':'' }}>Edit</option>
                        <option value="hapus" {{ request('jenis')=='hapus'?'selected':'' }}>Hapus</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label small fw-bold text-muted">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-control">
                </div>
                <div class="col-lg-2 col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-search w-100 text-white">
                        Cari Data
                    </button>
                </div>
            </form>
        </div>

        <div class="table-container">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pengguna</th>
                            <th>Waktu Aktivitas</th>
                            <th>Aktivitas</th>
                            <th>Keterangan Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logAktivitas as $i => $log)
                        <tr>
                            <td class="text-muted fw-medium">{{ $logAktivitas->firstItem() + $i }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-text me-3 bg-light rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 35px; height: 35px;">
                                        {{ substr($log->nama, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $log->nama }}</div>
                                        @php
                                            $roleBadge = [
                                                'admin' => 'bg-soft-danger',
                                                'petugas' => 'bg-soft-info',
                                                'peminjam' => 'bg-soft-success'
                                            ][$log->role] ?? 'bg-light text-dark';
                                        @endphp
                                        <span class="badge-soft {{ $roleBadge }}">{{ strtoupper($log->role) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-medium">{{ $log->created_at->format('d M Y') }}</div>
                                <div class="small text-muted"><i class="bi bi-clock me-1"></i>{{ $log->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td>
                                @php
                                    $act = [
                                        'login'   => ['icon' => 'bi-box-arrow-in-right', 'class' => 'bg-soft-success', 'label' => 'Login'],
                                        'logout'  => ['icon' => 'bi-box-arrow-right', 'class' => 'bg-soft-secondary', 'label' => 'Logout'],
                                        'tambah'  => ['icon' => 'bi-plus-circle', 'class' => 'bg-soft-primary', 'label' => 'Tambah'],
                                        'edit'    => ['icon' => 'bi-pencil-square', 'class' => 'bg-soft-warning', 'label' => 'Edit'],
                                        'hapus'   => ['icon' => 'bi-trash', 'class' => 'bg-soft-danger', 'label' => 'Hapus'],
                                        'pinjam'  => ['icon' => 'bi-journal-arrow-down', 'class' => 'bg-soft-info', 'label' => 'Pinjam'],
                                        'kembali' => ['icon' => 'bi-journal-check', 'class' => 'bg-soft-success', 'label' => 'Kembali'],
                                    ][$log->jenis] ?? ['icon' => 'bi-info-circle', 'class' => 'bg-light', 'label' => 'Lainnya'];
                                @endphp
                                <div class="d-flex align-items-center">
                                    <div class="icon-box {{ $act['class'] }}">
                                        <i class="bi {{ $act['icon'] }}"></i>
                                    </div>
                                    <span class="fw-semibold text-dark">{{ $act['label'] }}</span>
                                </div>
                            </td>
                            <td>
                                <p class="mb-0 text-muted small lh-base" style="max-width: 300px;">
                                    {{ $log->keterangan }}
                                </p>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-5 text-center">
                                <img src="https://illustrations.popsy.co/gray/data-report.svg" alt="Empty" style="width: 120px;" class="mb-3 opacity-50">
                                <p class="text-muted">Ops! Tidak ada riwayat aktivitas ditemukan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 border-top bg-light bg-opacity-50">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <span class="text-muted small mb-3 mb-md-0">Data {{ $logAktivitas->firstItem() }} ke {{ $logAktivitas->lastItem() }} dari total {{ $logAktivitas->total() }}</span>
                    <div>
                        {{ $logAktivitas->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection