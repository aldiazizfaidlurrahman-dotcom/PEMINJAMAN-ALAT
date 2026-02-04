<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PeminjamanAdminController extends Controller
{
    // Histori peminjaman
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $query = Peminjaman::with(['pengguna', 'alat.kategori']);

        if ($search) {
            $query->whereHas('pengguna', function($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('username', 'like', "%$search%");
            })->orWhereHas('alat', function($q) use ($search) {
                $q->where('nama_alat', 'like', "%$search%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $peminjaman = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.dataPeminjaman', compact('peminjaman', 'search', 'status'));
    }

    // Histori pengembalian
    public function pengembalian(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $query = Peminjaman::with(['pengguna', 'alat.kategori'])
            ->where('status', 'dikembalikan');

        if ($search) {
            $query->whereHas('pengguna', function($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('username', 'like', "%$search%");
            })->orWhereHas('alat', function($q) use ($search) {
                $q->where('nama_alat', 'like', "%$search%");
            });
        }

        $pengembalian = $query->orderBy('tanggal_dikembalikan', 'desc')->paginate(10);

        return view('admin.dataPengembalian', compact('pengembalian', 'search'));
    }

    // Form edit peminjaman (popup)
    public function edit($id)
    {
        $peminjaman = Peminjaman::with(['pengguna', 'alat.kategori'])->findOrFail($id);
        return view('admin.editPeminjaman', compact('peminjaman'));
    }

    // Update peminjaman
    public function update(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $validated = $request->validate([
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'status' => 'required|in:menunggu,disetujui,ditolak,dikembalikan',
            'catatan' => 'nullable|string',
        ]);

        $peminjaman->update($validated);

        return response()->json(['success' => true, 'message' => 'Data berhasil diupdate']);
    }
}