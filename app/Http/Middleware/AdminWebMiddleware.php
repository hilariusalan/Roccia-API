<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminWebMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Pengecekan pertama: Apakah pengguna sudah login?
        if (!Auth::check()) {
            // Jika belum, redirect ke halaman login
            return redirect()->route('auth-login');
        }

        // Pengecekan kedua: Apakah pengguna yang sudah login adalah admin?
        if (!Auth::user()->is_admin) {
            // Jika bukan admin, tolak akses dengan 403 Forbidden
            // Ini akan menampilkan halaman 403 standar Laravel
            abort(403, 'Akses ditolak. Hanya admin yang diizinkan.');
        }

        // Jika semua pengecekan lolos, lanjutkan ke halaman yang dituju
        return $next($request);
    }
}
