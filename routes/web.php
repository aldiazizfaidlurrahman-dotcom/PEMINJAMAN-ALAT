<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\PenggunaController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\AlatController;
use App\Http\Controllers\Admin\PeminjamanAdminController;
use App\Http\Controllers\Admin\LogAktivitasController;
use App\Http\Controllers\Peminjam\PeminjamController;
use App\Http\Controllers\Petugas\PetugasController;

// Route login (publik, tidak perlu login)
Route::middleware('web')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.process');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Route dashboard (redirector)
Route::get('/dashboard', [LoginController::class, 'dashboard'])
    ->name('dashboard')
    ->middleware('checkLogin');

// ============================================
// Route Admin (hanya role admin)
// ============================================
Route::middleware(['checkLogin', 'checkRole:admin'])->group(function () {
    // Dashboard admin
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // CRUD Pengguna
    Route::resource('admin/pengguna', PenggunaController::class, [
        'names' => [
            'index' => 'admin.pengguna.index',
            'create' => 'admin.pengguna.create',
            'store' => 'admin.pengguna.store',
            'edit' => 'admin.pengguna.edit',
            'update' => 'admin.pengguna.update',
            'destroy' => 'admin.pengguna.destroy',
        ]
    ]);

    // CRUD Kategori
    Route::resource('admin/kategori', KategoriController::class, [
        'names' => [
            'index' => 'admin.kategori.index',
            'create' => 'admin.kategori.create',
            'store' => 'admin.kategori.store',
            'edit' => 'admin.kategori.edit',
            'update' => 'admin.kategori.update',
            'destroy' => 'admin.kategori.destroy',
        ]
    ]);

    // CRUD Alat
    Route::resource('admin/alat', AlatController::class, [
        'names' => [
            'index' => 'admin.alat.index',
            'create' => 'admin.alat.create',
            'store' => 'admin.alat.store',
            'edit' => 'admin.alat.edit',
            'update' => 'admin.alat.update',
            'destroy' => 'admin.alat.destroy',
        ]
    ]);

    // Log Aktivitas
    Route::get('/admin/log-aktivitas', [LogAktivitasController::class, 'index'])->name('admin.logAktivitas');

    Route::get('/admin/data-peminjaman', [PeminjamanAdminController::class, 'index'])->name('admin.dataPeminjaman');
    Route::get('/admin/data-pengembalian', [PeminjamanAdminController::class, 'pengembalian'])->name('admin.dataPengembalian');
    Route::get('/admin/data-peminjaman/{id}/edit', [PeminjamanAdminController::class, 'edit'])->name('admin.editPeminjaman');
    Route::put('/admin/data-peminjaman/{id}', [PeminjamanAdminController::class, 'update'])->name('admin.updatePeminjaman');
});

// ============================================
// Route Petugas (hanya role petugas)
// ============================================
Route::middleware(['checkLogin', 'checkRole:petugas'])->group(function () {
    Route::get('/petugas/dashboard', [PetugasController::class, 'dashboard'])->name('petugas.dashboard');
    
    // Peminjaman menunggu approval
    Route::get('/petugas/peminjaman/menunggu', [PetugasController::class, 'indexPeminjamanMenunggu'])->name('petugas.peminjaman.menunggu');
    Route::get('/petugas/peminjaman/{peminjaman}/approval', [PetugasController::class, 'showApprovalForm'])->name('petugas.peminjaman.approval');
    Route::post('/petugas/peminjaman/{peminjaman}/approve', [PetugasController::class, 'approve'])->name('petugas.peminjaman.approve');
    Route::post('/petugas/peminjaman/{peminjaman}/reject', [PetugasController::class, 'reject'])->name('petugas.peminjaman.reject');
    
    // Daftar semua peminjaman dengan filter
    Route::get('/petugas/peminjaman', [PetugasController::class, 'indexPeminjaman'])->name('petugas.peminjaman.index');
    
    // Pengembalian alat
    Route::get('/petugas/pengembalian', [PetugasController::class, 'indexPengembalian'])->name('petugas.pengembalian.index');
    Route::get('/petugas/pengembalian/{peminjaman}/form', [PetugasController::class, 'showPengembalianForm'])->name('petugas.pengembalian.form');
    Route::post('/petugas/pengembalian/{peminjaman}/process', [PetugasController::class, 'processPengembalian'])->name('petugas.pengembalian.process');
});

// ============================================
// Route Peminjam (hanya role peminjam)
// ============================================
Route::middleware(['checkLogin', 'checkRole:peminjam'])->group(function () {
    Route::get('/peminjam/dashboard', [PeminjamController::class, 'dashboard'])->name('peminjam.dashboard');
    
    // Daftar Alat
    Route::get('/peminjam/alat', [PeminjamController::class, 'indexAlat'])->name('peminjam.alat.index');
    Route::get('/peminjam/alat/{alat}/form', [PeminjamController::class, 'showPeminjamanForm'])->name('peminjam.alat.form');
    Route::post('/peminjam/alat/{alat}/peminjam', [PeminjamController::class, 'storePeminjaman'])->name('peminjam.alat.store');
    
    // Riwayat Peminjaman
    Route::get('/peminjam/peminjaman', [PeminjamController::class, 'indexPeminjaman'])->name('peminjam.peminjaman.index');
    Route::get('/peminjam/peminjaman/{peminjaman}', [PeminjamController::class, 'showPeminjaman'])->name('peminjam.peminjaman.show');
    Route::delete('/peminjam/peminjaman/{peminjaman}', [PeminjamController::class, 'cancelPeminjaman'])->name('peminjam.peminjaman.cancel');
});

// Halaman welcome (opsional)
Route::get('/', function () {
    return view('welcome');
});