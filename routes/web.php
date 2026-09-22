<?php

use Illuminate\Support\Facades\Route;

// Halaman Utama / Landing Page (Pengumuman & Kalender Umum)
Route::get('/', function () {
    if (\Illuminate\Support\Facades\Auth::check()) {
        return redirect()->route('dashboard.redirect');
    }
    $totalAspirasi = \App\Models\Pengaduan::count();
    $latestPengaduan = (object)[ 'ticket_code' => 'PLP-2026-X1Y2', 'warna_badge_status' => 'amber', 'label_status' => 'Sedang Diproses', 'status' => 'diproses' ];
    $daftarUuKema = \App\Models\UuKema::where('is_active', true)->latest()->get();
    return view('welcome', compact('totalAspirasi', 'latestPengaduan', 'daftarUuKema'));
})->name('home');

// Halaman Khusus Profil DPM (Tentang Kami)
Route::get('/tentang', \App\Livewire\Publik\TentangKami::class)->name('tentang');

Route::get('/uu-kema', function () {
    $daftarUuKema = \App\Models\UuKema::where('is_active', true)->latest()->get();
    return view('uu-kema', compact('daftarUuKema'));
})->name('uu-kema.publik');

// Halaman Legal & Privasi
Route::get('/syarat-ketentuan', \App\Livewire\Publik\SyaratKetentuan::class)->name('syarat');
Route::get('/kebijakan-privasi', \App\Livewire\Publik\KebijakanPrivasi::class)->name('privasi');

// =====================================================================
// OAUTH GOOGLE ROUTES
// =====================================================================
Route::get('auth/google', [\App\Http\Controllers\Auth\GoogleController::class, 'redirect'])->name('google.login');
Route::get('auth/google/callback', [\App\Http\Controllers\Auth\GoogleController::class, 'callback']);

// =====================================================================
// RUTE SETELAH LOGIN (Terlindungi Auth)
// =====================================================================
Route::middleware(['auth', 'verified'])->group(function () {

    // Profil (Bisa diakses semua role)
    Route::view('profile', 'profile.index')->name('profile');
    Route::get('/dashboard-redirect', function () {
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user->hasRole('admin')) return redirect()->route('admin.dashboard');
        if ($user->hasRole('staff_dewan')) return redirect()->route('dewan.dashboard');
        if ($user->isEksekutif()) return redirect()->route('eksekutif.dashboard');
        if ($user->hasRole('hmps') || $user->hasRole('ukm')) return redirect()->route('organisasi.dashboard');
        return redirect()->route('mahasiswa.dashboard');
    })->name('dashboard.redirect');

    // 1. ADMIN ROUTES
    Route::middleware('check.role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
        // Manajemen User (Livewire)
        Route::get('users', \App\Livewire\Admin\UserManagement::class)->name('users');
        // Manajemen Pengumuman
        Route::get('pengumuman', \App\Livewire\Admin\KelolaPengumuman::class)->name('pengumuman.index');
        // Log Aktivitas
        Route::get('log-aktivitas', \App\Livewire\Admin\LogAktivitas::class)->name('log-aktivitas');
        // Manajemen Pengaduan
        Route::get('pengaduan', \App\Livewire\StaffDewan\ManajemenPengaduan::class)->name('pengaduan.index');
        Route::get('pengaduan/{ticket_code}', \App\Livewire\StaffDewan\DetailPengaduan::class)->name('pengaduan.detail');
        // Pantau Evaluasi Proker
        Route::get('evaluasi-proker', \App\Livewire\StaffDewan\PantauEvaluasi::class)->name('evaluasi-proker.index');
        // Kelola Proker
        Route::get('proker', \App\Livewire\Organisasi\KelolaProker::class)->name('proker.index');
        // Kelola Kegiatan
        Route::get('kegiatan', \App\Livewire\Organisasi\KelolaKegiatan::class)->name('kegiatan.index');
        // Kelola UU Kema
        Route::get('uu-kema', \App\Livewire\Admin\KelolaUuKema::class)->name('uu-kema.index');
    });

    // EKSEKUTIF ROUTES (Direktur & Wakil Direktur)
    Route::middleware('check.role:direktur,wakil_direktur')->prefix('eksekutif')->name('eksekutif.')->group(function () {
        Route::get('dashboard', \App\Livewire\Eksekutif\Dashboard::class)->name('dashboard');
        // Manajemen Pengaduan (Read Only & Catatan Internal)
        Route::get('pengaduan', \App\Livewire\StaffDewan\ManajemenPengaduan::class)->name('pengaduan.index');
        Route::get('pengaduan/{ticket_code}', \App\Livewire\StaffDewan\DetailPengaduan::class)->name('pengaduan.detail');
    });

    // 2. STAFF DEWAN ROUTES
    Route::middleware('check.role:staff_dewan')->prefix('dewan')->name('dewan.')->group(function () {
        Route::get('dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
        
        // Manajemen Pengaduan
        Route::get('pengaduan', \App\Livewire\StaffDewan\ManajemenPengaduan::class)->name('pengaduan.index');
        Route::get('pengaduan/{ticket_code}', \App\Livewire\StaffDewan\DetailPengaduan::class)->name('pengaduan.detail');
        
        // Manajemen Pengumuman DPM
        Route::get('pengumuman', \App\Livewire\Admin\KelolaPengumuman::class)->name('pengumuman.index');
        
        // Rute sensitif dengan middleware tambahan 'sensitif'
        // Route::get('pengaduan-sensitif', ...)->middleware('sensitif')->name('pengaduan.sensitif');
        
        // Pantau Evaluasi Proker
        Route::get('evaluasi-proker', \App\Livewire\StaffDewan\PantauEvaluasi::class)->name('evaluasi-proker.index');
        
        // Kelola Proker
        Route::get('proker', \App\Livewire\Organisasi\KelolaProker::class)->name('proker.index');
        
        // Kelola Kegiatan
        Route::get('kegiatan', \App\Livewire\Organisasi\KelolaKegiatan::class)->name('kegiatan.index');

        // Beri Evaluasi ke BEM (Staff Dewan bisa ikut memberi evaluasi)
        Route::get('evaluasi-bem', \App\Livewire\Mahasiswa\EvaluasiProker::class)->name('evaluasi-bem.index');
        // Kelola UU Kema
        Route::get('uu-kema', \App\Livewire\Admin\KelolaUuKema::class)->name('uu-kema.index');
    });

    // 3. HMPS / UKM ROUTES (Organisasi)
    Route::middleware('check.role:hmps,ukm')->prefix('organisasi')->name('organisasi.')->group(function () {
        Route::get('dashboard', \App\Livewire\Organisasi\Dashboard::class)->name('dashboard');
        
        // Manajemen Pengumuman Organisasi
        Route::get('pengumuman', \App\Livewire\Organisasi\KelolaPengumuman::class)->name('pengumuman.index');
        
        // Manajemen Kegiatan Organisasi
        Route::get('kegiatan', \App\Livewire\Organisasi\KelolaKegiatan::class)->name('kegiatan.index');

        // Manajemen Program Kerja
        Route::get('proker', \App\Livewire\Organisasi\KelolaProker::class)->name('proker.index');

        // Berikan Evaluasi ke BEM
        Route::get('evaluasi-bem', \App\Livewire\Mahasiswa\EvaluasiProker::class)->name('evaluasi-bem.index');

        // Pantau Evaluasi (Melihat kritikan masuk untuk prokernya sendiri)
        Route::get('evaluasi-proker', \App\Livewire\StaffDewan\PantauEvaluasi::class)->name('evaluasi-proker.index');
    });

    // 4. MAHASISWA ROUTES
    Route::middleware('check.role:mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::view('dashboard', 'livewire.mahasiswa.dashboard')->name('dashboard');
        
        Route::get('pengaduan/buat', \App\Livewire\Mahasiswa\BuatPengaduan::class)->name('pengaduan.buat');
        Route::get('pengaduan', \App\Livewire\Mahasiswa\DaftarPengaduan::class)->name('pengaduan.index');
        Route::get('pengaduan/{ticket_code}', \App\Livewire\Mahasiswa\DetailPengaduan::class)->name('pengaduan.detail');
    });
});

// ============================================================
// AUTO DEPLOY WEBHOOK (VERSI LINUX SERVER)
// ============================================================
Route::get('/update-rahasia-dpm', function () {
    $repoDir = base_path(); // Alamat folder Laravel (/app)
    

      // AUTO-PATCH .env for production domain
      $envFile = base_path('.env');
      if (file_exists($envFile)) {
          $env = file_get_contents($envFile);
          $env = preg_replace('/^APP_URL=.*/m', 'APP_URL=https://easpira-dpm.xie.my.id', $env);
            $env = preg_replace('/^APP_TIMEZONE=.*/m', 'APP_TIMEZONE=Asia/Jakarta', $env);
            if (!str_contains($env, 'APP_TIMEZONE=')) {
                $env .= "\nAPP_TIMEZONE=Asia/Jakarta\n";
            }
          $env = preg_replace('/^GOOGLE_REDIRECT_URI=.*/m', 'GOOGLE_REDIRECT_URI=https://easpira-dpm.xie.my.id/auth/google/callback', $env);
          file_put_contents($envFile, $env);
      }
    // 1. Mantra Sakti mengatasi "Dubious Ownership" (PENTING!)
    shell_exec("git config --global --add safe.directory \"$repoDir\"");
    
    // 2. Eksekusi Perintah Pembaruan
    $output1 = shell_exec("cd \"$repoDir\" && git fetch --all 2>&1");
    $output2 = shell_exec("cd \"$repoDir\" && git reset --hard origin/main 2>&1");
    
    // Pakai --no-interaction agar composer tidak nyangkut minta konfirmasi
    putenv('COMPOSER_HOME=/tmp');
    $output3 = shell_exec("cd \"$repoDir\" && composer install --no-interaction --prefer-dist --optimize-autoloader 2>&1");
    $output4 = shell_exec("cd \"$repoDir\" && php artisan migrate --force 2>&1");
      $output_roles = shell_exec("cd \"$repoDir\" && php artisan db:seed --class=RoleSeeder --force 2>&1");
      $output_katseed = shell_exec("cd \"$repoDir\" && php artisan db:seed --class=KategoriPengaduanSeeder --force 2>&1");
      $output_dbseed = shell_exec("cd \"$repoDir\" && php artisan db:seed --class=DatabaseSeeder --force 2>&1");
    $output_optimize = shell_exec("cd \"$repoDir\" && php artisan optimize 2>&1");
    $output_link = shell_exec("cd \"$repoDir\" && php artisan storage:link --force 2>&1");
    
    // Catatan: Jika NPM/Node.js belum terinstall di Docker ini, outputnya mungkin "command not found"
    $output5 = shell_exec("cd \"$repoDir\" && npm install 2>&1");
    $output6 = shell_exec("cd \"$repoDir\" && npm run build 2>&1");
    
    return "<h1 style='color:green;'>Berhasil Menarik Kodingan Baru & Update Sistem oleh MSS!</h1>
            <h3>Laporan Log:</h3>
            <pre style='background:#333;color:#0f0;padding:20px;border-radius:10px;'>
[GIT FETCH & PULL]
" . htmlspecialchars((string) $output1) . "
" . htmlspecialchars((string) $output2) . "

[COMPOSER INSTALL]
" . htmlspecialchars((string) $output3) . "

[DATABASE MIGRATE & SEED]
" . htmlspecialchars((string) $output4) . "
" . htmlspecialchars((string) $output_roles) . "
" . htmlspecialchars((string) $output_katseed) . "
" . htmlspecialchars((string) $output_dbseed) . "

[OPTIMIZE & CACHE]
" . htmlspecialchars((string) $output_optimize) . "
" . htmlspecialchars((string) $output_link) . "

[NPM BUILD (TAMPILAN)]
" . htmlspecialchars((string) $output5) . "
" . htmlspecialchars((string) $output6) . "
            </pre>";
});

Route::post('logout', function (\App\Livewire\Actions\Logout $logout) {
    $logout();
    return redirect('/');
})->name('logout');


// Route pembantu penyedia file storage (menjamin file lampiran selalu bisa dibuka tanpa 404)
Route::get('storage/{path}', function ($path) {
    $candidates = [
        storage_path('app/public/' . $path),
        storage_path('app/private/public/' . $path),
        storage_path('app/private/' . $path),
        storage_path('app/' . $path),
        public_path('storage/' . $path),
    ];
    
    foreach ($candidates as $filePath) {
        if (file_exists($filePath) && !is_dir($filePath)) {
            $mime = mime_content_type($filePath) ?: 'application/octet-stream';
            $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            if ($ext === 'heic' || $ext === 'heif') {
                $mime = 'image/heic';
            }
            return response()->file($filePath, [
                'Content-Type' => $mime,
                'Access-Control-Allow-Origin' => '*',
            ]);
        }
    }
    abort(404, 'File lampiran tidak ditemukan di server.');
})->where('path', '.*');

require __DIR__.'/auth.php';





















// Temporary: Lihat error log server (hapus setelah debug selesai)
Route::get('/cek-log-error', function () {
    $logFile = storage_path('logs/laravel.log');
    if (!file_exists($logFile)) return 'Log file tidak ada.';
    $lines = array_slice(file($logFile), -80);
    return '<pre style="background:#1a1a1a;color:#ff6b6b;padding:20px;font-size:11px;white-space:pre-wrap;">' 
        . htmlspecialchars(implode('', $lines)) . '</pre>';
});







