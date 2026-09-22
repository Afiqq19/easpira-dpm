<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-600 text-white flex items-center justify-center shadow-lg shadow-indigo-500/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
            <div>
                <h2 class="font-heading font-bold text-xl text-slate-800 leading-tight">Profil Pengguna</h2>
                <p class="text-sm text-slate-500 font-medium">Kelola informasi data diri, akun kampus, dan keamanan sistem Anda.</p>
            </div>
        </div>
    </x-slot>

    @php
        $user = Auth::user();
        $userRole = 'Mahasiswa';
        $roleColor = 'bg-indigo-50 text-indigo-700 border-indigo-200';
        if ($user->hasRole('admin')) {
            $userRole = 'Administrator';
            $roleColor = 'bg-rose-50 text-rose-700 border-rose-200';
        } elseif ($user->hasRole('staff_dewan')) {
            $userRole = 'Staff Dewan (DPM)';
            $roleColor = 'bg-purple-50 text-purple-700 border-purple-200';
        } elseif ($user->hasRole('direktur')) {
            $userRole = 'Direktur Utama';
            $roleColor = 'bg-blue-50 text-blue-700 border-blue-200';
        } elseif ($user->hasRole('wakil_direktur')) {
            $userRole = 'Wakil Direktur';
            $roleColor = 'bg-cyan-50 text-cyan-700 border-cyan-200';
        } elseif ($user->hasRole('hmps')) {
            $userRole = 'Pengurus HMPS';
            $roleColor = 'bg-amber-50 text-amber-700 border-amber-200';
        } elseif ($user->hasRole('ukm')) {
            $userRole = 'Pengurus UKM';
            $roleColor = 'bg-teal-50 text-teal-700 border-teal-200';
        }

        $totalLaporan = $user->pengaduans()->count();
        $totalSelesai = $user->pengaduans()->where('status', 'selesai')->count();
    @endphp

    <div class="max-w-6xl mx-auto space-y-6">

        <!-- Executive Hero Profile Card -->
        <div class="relative overflow-hidden glass rounded-3xl p-6 sm:p-8 border border-white/70 shadow-xl shadow-slate-200/50">
            <div class="absolute top-0 right-0 -mt-12 -mr-12 w-64 h-64 bg-gradient-to-br from-indigo-400/20 to-purple-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-0 left-1/3 -mb-12 w-48 h-48 bg-violet-400/15 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-center md:items-start gap-6">
                <!-- Avatar with Glowing Ring -->
                <div class="relative flex-shrink-0">
                    <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-3xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-purple-600 p-1 shadow-xl shadow-indigo-500/30 ring-4 ring-white">
                        <div class="w-full h-full rounded-[22px] bg-slate-900 flex items-center justify-center text-white text-3xl sm:text-4xl font-extrabold shadow-inner overflow-hidden">
                            @if($user->avatar)
                                <img src="{{ $user->avatar }}" class="w-full h-full object-cover" alt="{{ $user->nama }}">
                            @else
                                {{ strtoupper(substr($user->nama ?? $user->name ?? 'M', 0, 1)) }}
                            @endif
                        </div>
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full bg-emerald-500 border-2 border-white flex items-center justify-center shadow-md" title="Akun Aktif">
                        <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                    </div>
                </div>

                <!-- User Details -->
                <div class="flex-1 text-center md:text-left space-y-2">
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-2.5">
                        <h1 class="text-2xl sm:text-3xl font-heading font-extrabold text-slate-800">
                            {{ $user->nama ?? $user->name }}
                        </h1>
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border {{ $roleColor }} shadow-sm">
                            {{ $userRole }}
                        </span>
                    </div>

                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-y-1.5 gap-x-4 text-xs text-slate-500 font-medium">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            {{ $user->email }}
                        </span>
                        @if($user->isEksekutif())
                            @if($user->prodi)
                                <span class="flex items-center gap-1.5 font-bold text-indigo-700">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    Jabatan: {{ $user->prodi }}
                                </span>
                            @endif
                        @else
                            @if($user->nim)
                                <span class="flex items-center gap-1.5 font-mono">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                    NIM: {{ $user->nim }}
                                </span>
                            @endif
                            @if($user->prodi)
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    {{ $user->prodi }}
                                </span>
                            @endif
                        @endif
                    </div>

                    <!-- Quick Badges -->
                    <div class="pt-2 flex flex-wrap items-center justify-center md:justify-start gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Status Akun: Aktif
                        </span>
                        @if($user->google_id || str_contains($user->email, '@students.polmed.ac.id'))
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-blue-50 text-blue-700 text-xs font-bold border border-blue-200">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
                                Google SSO Kampus Terverifikasi
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Stats Counters (For Students) -->
                @if($user->hasRole('mahasiswa'))
                    <div class="flex items-center gap-3 w-full md:w-auto justify-center pt-2 md:pt-0">
                        <div class="px-5 py-3.5 rounded-2xl bg-indigo-50/80 border border-indigo-100 text-center min-w-[100px]">
                            <span class="block text-2xl font-heading font-extrabold text-indigo-700">{{ $totalLaporan }}</span>
                            <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mt-0.5">Total Laporan</span>
                        </div>
                        <div class="px-5 py-3.5 rounded-2xl bg-emerald-50/80 border border-emerald-100 text-center min-w-[100px]">
                            <span class="block text-2xl font-heading font-extrabold text-emerald-700">{{ $totalSelesai }}</span>
                            <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mt-0.5">Kasus Tuntas</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Content Grid (2 Columns) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column: Forms -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Biodata Information Form -->
                <div class="glass rounded-3xl p-6 sm:p-8 border border-white/60 shadow-lg shadow-slate-200/50">
                    @if($user->isEksekutif())
                        <livewire:eksekutif.update-profile />
                    @else
                        <livewire:profile.update-profile-information-form />
                    @endif
                </div>

                <!-- Password Form -->
                <div class="glass rounded-3xl p-6 sm:p-8 border border-white/60 shadow-lg shadow-slate-200/50">
                    <livewire:profile.update-password-form />
                </div>
            </div>

            <!-- Right Column: Security & Privacy Cards -->
            <div class="space-y-6">
                
                <!-- Card 1: Enkripsi & Privasi -->
                <div class="glass rounded-3xl p-6 border border-white/60 shadow-lg shadow-slate-200/50 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-inner">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-800">Keamanan Identitas</h4>
                            <p class="text-[11px] text-slate-500">Standar Enkripsi Polmed</p>
                        </div>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Identitas pada laporan bertipe <strong>Anonim</strong> dienkripsi menggunakan algoritma <strong>AES-256-CBC</strong>. Staf tidak dapat melihat nama maupun NIM Anda.
                    </p>
                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs font-semibold text-emerald-700">
                        <span>Status Enkripsi:</span>
                        <span class="inline-flex items-center gap-1 font-bold">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            Aktif & Terlindungi
                        </span>
                    </div>
                </div>

                <!-- Card 2: Akses Cepat Sesi -->
                <div class="glass rounded-3xl p-6 border border-white/60 shadow-lg shadow-slate-200/50 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-inner">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-800">Sesi & Autentikasi</h4>
                            <p class="text-[11px] text-slate-500">e-Aspira DPM Polmed</p>
                        </div>
                    </div>

                    <div class="space-y-2.5 text-xs text-slate-600">
                        <div class="flex items-center justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-500">Metode Masuk:</span>
                            <span class="font-bold text-slate-800">{{ $user->google_id ? 'Google OAuth 2.0' : 'Email & Password' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-500">Domain Resmi:</span>
                            <span class="font-bold font-mono text-indigo-600">easpira-dpm.xie.my.id</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5">
                            <span class="text-slate-500">Terdaftar Sejak:</span>
                            <span class="font-bold text-slate-800">{{ $user->created_at ? $user->created_at->translatedFormat('d M Y') : '2026' }}</span>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold transition-colors flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Keluar dari Sesi Ini
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>
</x-app-layout>
