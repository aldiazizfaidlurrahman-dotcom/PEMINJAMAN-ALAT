<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Middleware ini mengecek apakah role pengguna sesuai dengan yang diizinkan
     * 
     * Cara penggunaan:
     * Route::middleware(['checkLogin', 'checkRole:admin'])->group(...);
     * Route::middleware(['checkLogin', 'checkRole:admin,petugas'])->group(...);
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $userRole = session('pengguna_role');

        // Jika role pengguna tidak ada dalam daftar role yang diizinkan
        if (!in_array($userRole, $roles)) {
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        return $next($request);
    }
}
