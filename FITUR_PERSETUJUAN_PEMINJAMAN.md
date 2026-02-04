# Fitur Persetujuan Peminjaman Oleh Petugas

## Deskripsi Fitur
Fitur ini memungkinkan petugas untuk menyetujui atau menolak permintaan peminjaman alat dari pengguna. Ketika peminjaman disetujui, stok alat akan berkurang secara otomatis.

## Ketentuan Fitur
1. **Petugas bisa setujui / tolak** - Petugas memiliki dua pilihan: menyetujui atau menolak peminjaman
2. **Jika disetujui stok alat berkurang** - Ketika peminjaman disetujui, stok alat akan otomatis berkurang 1 unit
3. **Update status peminjaman** - Status peminjaman akan diubah sesuai dengan keputusan petugas

## Status Peminjaman
- **menunggu** - Peminjaman sedang menunggu persetujuan dari petugas
- **disetujui** - Peminjaman telah disetujui, stok alat berkurang
- **ditolak** - Peminjaman telah ditolak dengan alasan tertentu
- **dikembalikan** - Alat telah dikembalikan oleh peminjam

## Komponen yang Dibuat

### 1. Controller - `PetugasController`
**File:** `app/Http/Controllers/Petugas/PetugasController.php`

#### Methods:
- `dashboard()` - Menampilkan dashboard petugas dengan statistik peminjaman
- `indexPeminjamanMenunggu()` - Menampilkan daftar peminjaman menunggu approval
- `showApprovalForm(Peminjaman $peminjaman)` - Menampilkan form persetujuan
- `approve(Request $request, Peminjaman $peminjaman)` - Menyetujui peminjaman dan kurangi stok
- `reject(Request $request, Peminjaman $peminjaman)` - Menolak peminjaman dengan alasan
- `indexPeminjaman(Request $request)` - Menampilkan daftar peminjaman dengan filter status

#### Fitur Keamanan:
- Validasi status peminjaman sebelum diproses
- Pengecekan stok alat sebelum persetujuan
- Database transaction untuk memastikan data konsisten
- Validasi alasan penolakan (minimal 10 karakter, maksimal 500 karakter)

### 2. Database Migration
**File:** `database/migrations/2026_01_18_141022_create_peminjaman_table.php`

**Perubahan:**
- Tambah field `catatan` (nullable text) untuk menyimpan alasan penolakan atau catatan lainnya

### 3. Model - `Peminjaman`
**File:** `app/Models/Peminjaman.php`

**Perubahan:**
- Tambah `catatan` ke array `$fillable`

### 4. Routes
**File:** `routes/web.php`

**Route yang ditambahkan:**
```php
// Middleware: checkLogin, checkRole:petugas
Route::get('/petugas/dashboard', [PetugasController::class, 'dashboard'])->name('petugas.dashboard');
Route::get('/petugas/peminjaman/menunggu', [PetugasController::class, 'indexPeminjamanMenunggu'])->name('petugas.peminjaman.menunggu');
Route::get('/petugas/peminjaman/{peminjaman}/approval', [PetugasController::class, 'showApprovalForm'])->name('petugas.peminjaman.approval');
Route::post('/petugas/peminjaman/{peminjaman}/approve', [PetugasController::class, 'approve'])->name('petugas.peminjaman.approve');
Route::post('/petugas/peminjaman/{peminjaman}/reject', [PetugasController::class, 'reject'])->name('petugas.peminjaman.reject');
Route::get('/petugas/peminjaman', [PetugasController::class, 'indexPeminjaman'])->name('petugas.peminjaman.index');
```

### 5. Views

#### a. Dashboard Petugas - `resources/views/petugas/dashboard.blade.php`
**Update:**
- Tambah statistik peminjaman (menunggu, disetujui, ditolak, dikembalikan)
- Tampilkan card yang clickable ke masing-masing status peminjaman
- Update user info menggunakan `Auth::user()`

#### b. List Peminjaman Menunggu - `resources/views/petugas/peminjaman/menunggu.blade.php`
**Fitur:**
- Tampilkan daftar peminjaman dengan status "menunggu"
- Informasi: peminjam, alat, kategori, tanggal pinjam, tanggal kembali, status
- Tombol "Proses" untuk membuka form persetujuan
- Pagination support (15 per halaman)
- Alert untuk success/error message

#### c. Form Persetujuan - `resources/views/petugas/peminjaman/approval.blade.php`
**Fitur:**
- Tampilkan detail lengkap peminjaman (peminjam, alat, tanggal, stok)
- Dua tombol: "Setujui Peminjaman" dan "Tolak Peminjaman"
- Modal form untuk menolak dengan field "Alasan Penolakan"
- Validasi client-side untuk alasan penolakan
- Informasi stok alat dengan badge (Stok Cukup/Stok Habis)
- Tombol setujui disabled jika stok habis

#### d. Daftar Semua Peminjaman - `resources/views/petugas/peminjaman/index.blade.php`
**Fitur:**
- Filter tabs untuk mengubah status (menunggu, disetujui, ditolak, dikembalikan)
- Tampilkan daftar peminjaman berdasarkan status terpilih
- Pagination support (15 per halaman)
- Tombol "Lihat Detail" untuk membuka form persetujuan

## Alur Kerja

### 1. Petugas Login
- Petugas login dengan role "petugas"
- Akan diarahkan ke dashboard petugas

### 2. Dashboard Petugas
- Menampilkan statistik peminjaman
- Card "Peminjaman Menunggu" dengan jumlah peminjaman yang menunggu approval
- Click pada card untuk melihat daftar peminjaman menunggu

### 3. List Peminjaman Menunggu
- Tampilkan semua peminjaman dengan status "menunggu"
- Klik tombol "Proses" pada baris peminjaman

### 4. Form Persetujuan
- Tampilkan detail lengkap peminjaman
- Petugas memilih untuk menyetujui atau menolak:
  - **Setujui**: Stok alat berkurang 1, status berubah menjadi "disetujui"
  - **Tolak**: Modal form muncul, masukkan alasan, status berubah menjadi "ditolak"

### 5. Peminjaman Lainnya
- Klik pada card di dashboard untuk melihat peminjaman dengan status lain (disetujui, ditolak, dikembalikan)

## Keamanan & Validasi

### Server-side:
- Pengecekan role petugas di middleware
- Validasi status peminjaman sebelum diproses (hanya status "menunggu" yang bisa diproses)
- Pengecekan stok alat sebelum menyetujui
- Database transaction untuk konsistensi data
- Validasi length alasan penolakan

### Client-side:
- Tombol setujui disabled jika stok habis
- Validasi modal form alasan penolakan
- Alert success/error message

## Database Schema Update

### Tabel `peminjaman`
```sql
ALTER TABLE peminjaman ADD COLUMN catatan TEXT NULL;
```

## Testing Checklist

- [ ] Petugas bisa melihat dashboard dengan statistik peminjaman
- [ ] Click card membuka daftar peminjaman berdasarkan status
- [ ] List peminjaman menampilkan data dengan benar
- [ ] Click "Proses" membuka form persetujuan
- [ ] Setujui peminjaman mengubah status menjadi "disetujui"
- [ ] Setujui peminjaman mengurangi stok alat 1 unit
- [ ] Tolak peminjaman membuka modal form
- [ ] Tolak peminjaman mengubah status menjadi "ditolak" dengan catatan
- [ ] Tombol setujui disabled jika stok habis
- [ ] Pagination bekerja dengan baik
- [ ] Alert message ditampilkan setelah proses

## Catatan Penting

1. **Database Migration**: Jalankan migration untuk menambahkan field `catatan`:
   ```bash
   php artisan migrate
   ```

2. **Middleware Access**: Pastikan middleware `checkRole:petugas` sudah dikonfigurasi dengan benar

3. **Auth Helper**: Fitur ini menggunakan `Auth::user()` untuk mengambil data user login, pastikan helper ini sudah dikonfigurasi

4. **Stok Alat**: Proses approval otomatis mengurangi stok alat, pastikan stok tidak negatif
