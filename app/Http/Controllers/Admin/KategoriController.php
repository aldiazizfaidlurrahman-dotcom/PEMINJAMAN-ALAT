<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Helpers\LogHelper;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Tampilkan daftar kategori
     */
    public function index()
    {
        $kategori = Kategori::withCount('alats')->orderBy('nama_kategori')->paginate(10);
        return view('admin.kategori.index', compact('kategori'));
    }

    /**
     * Tampilkan form tambah kategori
     */
    public function create()
    {
        return view('admin.kategori.create');
    }

    /**
     * Simpan kategori baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi',
            'nama_kategori.unique' => 'Nama kategori sudah ada',
        ]);

        $kategori = Kategori::create($validated);

        // Log aktivitas tambah kategori
        LogHelper::log('tambah', 'Menambah kategori: ' . $kategori->nama_kategori);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    /**
     * Tampilkan form edit kategori
     */
    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    /**
     * Update kategori
     */
    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori,' . $kategori->id,
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi',
            'nama_kategori.unique' => 'Nama kategori sudah ada',
        ]);

        $kategori->update($validated);

        // Log aktivitas edit kategori
        LogHelper::log('edit', 'Mengedit kategori: ' . $kategori->nama_kategori);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diubah');
    }

    /**
     * Hapus kategori
     */
    public function destroy(Kategori $kategori)
    {
        // Cek apakah kategori masih memiliki alat
        if ($kategori->alats()->count() > 0) {
            return redirect()->route('admin.kategori.index')
                ->with('error', 'Tidak bisa menghapus kategori yang masih memiliki alat');
        }

        $nama = $kategori->nama_kategori;
        $kategori->delete();

        // Log aktivitas hapus kategori
        LogHelper::log('hapus', 'Menghapus kategori: ' . $nama);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}