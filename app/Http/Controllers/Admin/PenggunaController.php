<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use App\Helpers\LogHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    /**
     * Tampilkan daftar pengguna
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $pengguna = Pengguna::query()
            ->when($search, function ($query) use ($search) {
                return $query->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('username', 'like', '%' . $search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('admin.pengguna.index', compact('pengguna', 'search'));
    }

    /**
     * Tampilkan form tambah pengguna
     */
    public function create()
    {
        return view('admin.pengguna.create');
    }

    /**
     * Simpan pengguna baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:pengguna,username',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,petugas,peminjam',
            'status' => 'required|boolean',
        ], [
            'nama.required' => 'Nama wajib diisi',
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Password konfirmasi tidak cocok',
            'role.required' => 'Role wajib dipilih',
        ]);

        // Hash password
        $validated['password'] = Hash::make($validated['password']);

        $pengguna = Pengguna::create($validated);

        // Log aktivitas tambah pengguna
        LogHelper::log('tambah', 'Menambah pengguna: ' . $pengguna->nama);

        return redirect()->route('admin.pengguna.index')
            ->with('success', 'Pengguna berhasil ditambahkan');
    }

    /**
     * Tampilkan form edit pengguna
     */
    public function edit(Pengguna $pengguna)
    {
        return view('admin.pengguna.edit', compact('pengguna'));
    }

    /**
     * Update pengguna
     */
    public function update(Request $request, Pengguna $pengguna)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:pengguna,username,' . $pengguna->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:admin,petugas,peminjam',
            'status' => 'required|boolean',
        ], [
            'nama.required' => 'Nama wajib diisi',
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah terdaftar',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Password konfirmasi tidak cocok',
            'role.required' => 'Role wajib dipilih',
        ]);

        // Hash password jika ada input password baru
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $pengguna->update($validated);

        // Log aktivitas edit pengguna
        LogHelper::log('edit', 'Mengedit pengguna: ' . $pengguna->nama);

        return redirect()->route('admin.pengguna.index')
            ->with('success', 'Pengguna berhasil diubah');
    }

    /**
     * Hapus pengguna
     */
    public function destroy(Pengguna $pengguna)
    {
        // Jangan hapus user yang sedang login
        if ($pengguna->id === session('pengguna_id')) {
            return redirect()->route('admin.pengguna.index')
                ->with('error', 'Tidak bisa menghapus pengguna yang sedang login');
        }

        $nama = $pengguna->nama;
        $pengguna->delete();

        // Log aktivitas hapus pengguna
        LogHelper::log('hapus', 'Menghapus pengguna: ' . $nama);

        return redirect()->route('admin.pengguna.index')
            ->with('success', 'Pengguna berhasil dihapus');
    }
}