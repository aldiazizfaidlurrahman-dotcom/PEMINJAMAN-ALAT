<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLogin
{
    /**
     * Handle an incoming request.
     * Middleware ini mengecek apakah pengguna sudah login
     * Jika belum login, redirect ke halaman login
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah session pengguna_id ada
        if (!session('pengguna_id')) {
            return redirect()->route('login')
                ->with('error', 'Silahkan login terlebih dahulu');
        }

        return $next($request);
    }
}
