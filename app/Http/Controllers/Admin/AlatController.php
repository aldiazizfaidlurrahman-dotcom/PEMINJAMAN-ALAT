<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Kategori;
use App\Helpers\LogHelper;
use Illuminate\Http\Request;

class AlatController extends Controller
{
    /**
     * Tampilkan daftar alat
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $kategoriFilter = $request->input('kategori');
        
        $alat = Alat::query()
            ->with('kategori')
            ->search($search)
            ->byKategori($kategoriFilter)
            ->orderBy('nama_alat', 'asc')
            ->paginate(10);

        $kategori = Kategori::orderBy('nama_kategori', 'asc')->get();

        return view('admin.alat.index', compact('alat', 'kategori', 'search', 'kategoriFilter'));
    }

    /**
     * Tampilkan form tambah alat
     */
    public function create()
    {
        $kategori = Kategori::orderBy('nama_kategori', 'asc')->get();
        return view('admin.alat.create', compact('kategori'));
    }

    /**
     * Simpan alat baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'nama_alat' => 'required|string|max:100',
            'kondisi' => 'required|in:baik,rusak,diperbaiki',
            'stok' => 'required|integer|min:0',
        ], [
            'kategori_id.required' => 'Kategori wajib dipilih',
            'kategori_id.exists' => 'Kategori tidak valid',
            'nama_alat.required' => 'Nama alat wajib diisi',
            'kondisi.required' => 'Kondisi wajib dipilih',
            'stok.required' => 'Stok wajib diisi',
            'stok.integer' => 'Stok harus berupa angka',
            'stok.min' => 'Stok tidak boleh negatif',
        ]);

        $alat = Alat::create($validated);

        // Log aktivitas tambah alat
        LogHelper::log('tambah', 'Menambah data alat: ' . $alat->nama_alat);

        return redirect()->route('admin.alat.index')
            ->with('success', 'Alat berhasil ditambahkan');
    }

    /**
     * Tampilkan form edit alat
     */
    public function edit(Alat $alat)
    {
        $kategori = Kategori::orderBy('nama_kategori', 'asc')->get();
        return view('admin.alat.edit', compact('alat', 'kategori'));
    }

    /**
     * Update alat
     */
    public function update(Request $request, Alat $alat)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'nama_alat' => 'required|string|max:100',
            'kondisi' => 'required|in:baik,rusak,diperbaiki',
            'stok' => 'required|integer|min:0',
        ], [
            'kategori_id.required' => 'Kategori wajib dipilih',
            'kategori_id.exists' => 'Kategori tidak valid',
            'nama_alat.required' => 'Nama alat wajib diisi',
            'kondisi.required' => 'Kondisi wajib dipilih',
            'stok.required' => 'Stok wajib diisi',
            'stok.integer' => 'Stok harus berupa angka',
            'stok.min' => 'Stok tidak boleh negatif',
        ]);

        $alat->update($validated);

        // Log aktivitas edit alat
        LogHelper::log('edit', 'Mengedit data alat: ' . $alat->nama_alat);

        return redirect()->route('admin.alat.index')
            ->with('success', 'Alat berhasil diubah');
    }

    /**
     * Hapus alat
     */
    public function destroy(Alat $alat)
    {
        $nama = $alat->nama_alat;
        $alat->delete();

        // Log aktivitas hapus alat
        LogHelper::log('hapus', 'Menghapus data alat: ' . $nama);

        return redirect()->route('admin.alat.index')
            ->with('success', 'Alat berhasil dihapus');
    }
}