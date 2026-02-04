<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Helpers\LogHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLoginForm()
    {
        if (session('pengguna_id')) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username harus diisi',
            'password.required' => 'Password harus diisi',
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        $pengguna = Pengguna::where('username', $username)->first();

        if (!$pengguna || !Hash::check($password, $pengguna->password)) {
            return back()
                ->withInput()
                ->with('error', 'Username atau password salah');
        }

        if (!$pengguna->isActive()) {
            return back()
                ->with('error', 'Akun Anda tidak aktif. Silahkan hubungi administrator');
        }

        // Simpan session login
        session([
            'pengguna_id' => $pengguna->id,
            'pengguna_nama' => $pengguna->nama,
            'pengguna_username' => $pengguna->username,
            'pengguna_role' => $pengguna->role,
        ]);

        // Catat log aktivitas login
        LogHelper::log('login', 'Login berhasil sebagai ' . $pengguna->role);

        return redirect()->route('dashboard')
            ->with('success', 'Login berhasil. Selamat datang ' . $pengguna->nama);
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        // Catat log aktivitas logout
        LogHelper::log('logout', 'Logout dari sistem');

        $request->session()->flush();
        $request->session()->invalidate();

        return redirect()->route('login')
            ->with('success', 'Anda telah logout');
    }

    /**
     * Redirect ke dashboard sesuai role
     */
    public function dashboard()
    {
        $role = session('pengguna_role');

        return match ($role) {
            'admin' => redirect('/admin/dashboard'),
            'petugas' => redirect('/petugas/dashboard'),
            'peminjam' => redirect('/peminjam/dashboard'),
            default => redirect()->route('login')->with('error', 'Role tidak valid'),
        };
    }
}