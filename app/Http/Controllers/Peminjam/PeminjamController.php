<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Helpers\LogHelper;
use Illuminate\Http\Request;

class PeminjamController extends Controller
{
    /**
     * Tampilkan dashboard peminjam
     */
    public function dashboard()
    {
        return view('peminjam.dashboard');
    }

    /**
     * Tampilkan daftar alat tersedia untuk peminjaman
     */
    public function indexAlat(Request $request)
    {
        $search = $request->input('search');
        $kategoriFilter = $request->input('kategori');

        $alat = Alat::with('kategori')
            ->where('stok', '>', 0)
            ->search($search)
            ->byKategori($kategoriFilter)
            ->paginate(12);

        return view('peminjam.alat.index', compact('alat', 'search', 'kategoriFilter'));
    }

    /**
     * Tampilkan form peminjaman alat
     */
    public function showPeminjamanForm($alatId)
    {
        $alat = Alat::with('kategori')->findOrFail($alatId);

        // Cek apakah alat masih tersedia
        if ($alat->stok <= 0) {
            return redirect()->route('peminjam.alat.index')
                ->with('error', 'Alat tidak tersedia saat ini');
        }

        return view('peminjam.alat.form-peminjaman', compact('alat'));
    }

    /**
     * Simpan peminjaman baru
     */
    public function storePeminjaman(Request $request, $alatId)
    {
        $alat = Alat::findOrFail($alatId);

        // Validasi
        $request->validate([
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
        ], [
            'tanggal_pinjam.required' => 'Tanggal peminjaman harus diisi',
            'tanggal_pinjam.date' => 'Tanggal peminjaman harus berupa tanggal',
            'tanggal_pinjam.after_or_equal' => 'Tanggal peminjaman tidak boleh di masa lalu',
            'tanggal_kembali.required' => 'Tanggal pengembalian harus diisi',
            'tanggal_kembali.date' => 'Tanggal pengembalian harus berupa tanggal',
            'tanggal_kembali.after' => 'Tanggal pengembalian harus setelah tanggal peminjaman',
        ]);

        // Cek alat masih tersedia
        if ($alat->stok <= 0) {
            return back()->with('error', 'Alat tidak tersedia. Stok habis');
        }

        // Simpan peminjaman
        $peminjaman = Peminjaman::create([
            'pengguna_id' => session('pengguna_id'),
            'alat_id' => $alatId,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'status' => 'menunggu',
        ]);

        // Log aktivitas peminjaman
        LogHelper::log('pinjam', 'Peminjam melakukan peminjaman alat: ' . $alat->nama_alat);

        return redirect()->route('peminjam.peminjaman.index')
            ->with('success', 'Permohonan peminjaman berhasil dikirim. Status: Menunggu persetujuan');
    }

    /**
     * Tampilkan daftar peminjaman pengguna
     */
    public function indexPeminjaman(Request $request)
    {
        $status = $request->input('status');

        $peminjaman = Peminjaman::with('alat.kategori')
            ->byPengguna(session('pengguna_id'))
            ->when($status, function ($query) use ($status) {
                return $query->byStatus($status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $statuses = [
            'menunggu' => 'Menunggu',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            'dikembalikan' => 'Dikembalikan',
        ];

        return view('peminjam.peminjaman.index', compact('peminjaman', 'status', 'statuses'));
    }

    /**
     * Tampilkan detail peminjaman
     */
    public function showPeminjaman($peminjamanId)
    {
        $peminjaman = Peminjaman::with('alat.kategori')
            ->where('pengguna_id', session('pengguna_id'))
            ->findOrFail($peminjamanId);

        return view('peminjam.peminjaman.show', compact('peminjaman'));
    }

    /**
     * Batal peminjaman (hanya jika status menunggu)
     */
    public function cancelPeminjaman($peminjamanId)
    {
        $peminjaman = Peminjaman::where('pengguna_id', session('pengguna_id'))
            ->findOrFail($peminjamanId);

        // Cek status
        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Hanya peminjaman dengan status "Menunggu" yang dapat dibatalkan');
        }

        $alat = $peminjaman->alat->nama_alat ?? '-';
        $peminjaman->delete();

        // Log aktivitas batal peminjaman
        LogHelper::log('hapus', 'Peminjam membatalkan permohonan peminjaman alat: ' . $alat);

        return redirect()->route('peminjam.peminjaman.index')
            ->with('success', 'Permohonan peminjaman berhasil dibatalkan');
    }
}