<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Permission\Models\Role;

class CheckRole
{
    /**
     * Middleware untuk memeriksa role pengguna.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Satu atau lebih role yang diizinkan
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if (!$request->user()->is_active) {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'Akun Anda telah dinonaktifkan. Hubungi admin.');
        }

        $user = $request->user();

        // Pastikan jika user belum memiliki role apapun, berikan 'mahasiswa'
        if ($user->roles->isEmpty()) {
            Role::firstOrCreate(['name' => 'mahasiswa', 'guard_name' => 'web']);
            $user->assignRole('mahasiswa');
            $user->load('roles');
        }

        // Admin memiliki hak akses penuh ke semua halaman untuk pengujian & manajemen
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        // Cek apakah user memiliki salah satu dari role yang diizinkan
        if (!$user->hasAnyRole($roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
