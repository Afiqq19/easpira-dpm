<div x-cloak :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'" 
     class="fixed md:relative inset-y-0 left-0 w-72 md:w-64 flex flex-col h-screen z-40 
            transition-transform duration-300 ease-in-out
            bg-gradient-to-b from-[#0B0F19] via-[#0F172A] to-[#090D16] border-r border-white/[0.08] shadow-2xl shrink-0 select-none">

    <!-- Brand Logo & Header -->
    <div class="h-16 flex items-center justify-between px-5 border-b border-white/[0.08] flex-shrink-0 bg-white/[0.02]">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-500 via-indigo-600 to-violet-600 flex items-center justify-center p-1.5 shadow-lg shadow-indigo-500/25 border border-white/20 flex-shrink-0">
                <img src="{{ asset('images/icon_dpm.png') }}" class="w-full h-full object-contain filter drop-shadow" alt="Logo DPM">
            </div>
            <div class="flex flex-col">
                <span class="font-heading font-extrabold text-white text-base tracking-tight leading-none">
                    e-Aspira <span class="bg-gradient-to-r from-indigo-400 to-violet-400 bg-clip-text text-transparent">DPM</span>
                </span>
                <span class="text-[10px] text-slate-400 font-semibold tracking-wider uppercase mt-1">Polmed Official</span>
            </div>
        </div>
        <!-- Close button (Mobile Only) -->
        <button @click="sidebarOpen = false" class="md:hidden p-1.5 rounded-xl text-slate-400 hover:text-white hover:bg-white/10 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <!-- User Profile Widget (Glass Card) -->
    <div class="p-3.5 border-b border-white/[0.08] flex-shrink-0">
        <a href="{{ route('profile') }}" wire:navigate class="flex items-center gap-3 p-2.5 rounded-2xl bg-white/[0.03] hover:bg-white/[0.07] border border-white/[0.06] hover:border-indigo-500/30 transition-all duration-200 group">
            <div class="relative flex-shrink-0">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 via-indigo-600 to-purple-600 p-0.5 shadow-md shadow-indigo-500/30 ring-1 ring-white/30">
                    <div class="w-full h-full rounded-[10px] bg-slate-900 flex items-center justify-center text-white font-extrabold text-sm overflow-hidden">
                        @if(auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" class="w-full h-full object-cover" alt="Avatar">
                        @else
                            {{ strtoupper(substr(auth()->user()->nama ?? auth()->user()->name ?? 'U', 0, 1)) }}
                        @endif
                    </div>
                </div>
                <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-emerald-500 border-2 border-slate-900" title="Aktif"></div>
            </div>
            <div class="overflow-hidden min-w-0 flex-1">
                <p class="font-bold text-white text-xs truncate group-hover:text-indigo-300 transition-colors">
                    {{ auth()->user()->nama ?? auth()->user()->name }}
                </p>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="text-[10px] font-bold text-indigo-400 capitalize truncate">
                        {{ auth()->user()->roles->first()->name ?? 'Pengguna' }}
                    </span>
                    @if(auth()->user()->google_id)
                        <span class="w-1 h-1 rounded-full bg-slate-600"></span>
                        <span class="text-[9px] font-bold text-emerald-400">SSO</span>
                    @endif
                </div>
            </div>
            <svg class="w-4 h-4 text-slate-500 group-hover:text-slate-300 group-hover:translate-x-0.5 transition-all flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
    </div>

    <!-- Navigation Menu Items -->
    <nav class="flex-1 overflow-y-auto py-3 px-3 space-y-1 custom-scrollbar">

        <!-- ADMIN MENU -->
        @role('admin')
            <div class="px-3 pt-2 pb-1 flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Menu Administrator</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs {{ $this->isActiveClass('admin.dashboard') }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.users') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs {{ $this->isActiveClass('admin.users') }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span>Manajemen User</span>
            </a>
            <a href="{{ route('admin.pengumuman.index') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs {{ $this->isActiveClass('admin.pengumuman.index') }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                <span>Pengumuman</span>
            </a>
            <a href="{{ route('admin.pengaduan.index') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs {{ $this->isActiveClass('admin.pengaduan.index') }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Data Pengaduan</span>
            </a>
            <a href="{{ route('admin.uu-kema.index') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs {{ $this->isActiveClass('admin.uu-kema.index') }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <span>Kelola UU Kema</span>
            </a>
        @endrole

        <!-- STAFF DEWAN MENU -->
        @role('staff_dewan')
            <div class="px-3 pt-2 pb-1 flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Menu Dewan</p>
            </div>
            <a href="{{ route('dewan.dashboard') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs {{ $this->isActiveClass('dewan.dashboard') }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('dewan.pengaduan.index') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs {{ $this->isActiveClass('dewan.pengaduan.index') }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Kelola Pengaduan</span>
            </a>
            <a href="{{ route('dewan.pengumuman.index') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs {{ $this->isActiveClass('dewan.pengumuman.index') }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                <span>Pengumuman</span>
            </a>
            <a href="{{ route('dewan.kegiatan.index') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs {{ $this->isActiveClass('dewan.kegiatan.index') }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span>Kegiatan DPM</span>
            </a>
            <a href="{{ route('dewan.aspirasi-proker.index') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs {{ $this->isActiveClass('dewan.aspirasi-proker.index') }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                <span>Aspirasi Proker</span>
            </a>
        @endrole

        <!-- ORGANISASI (HMPS/UKM) MENU -->
        @if(auth()->user()->hasRole('hmps') || auth()->user()->hasRole('ukm'))
            <div class="px-3 pt-2 pb-1 flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Menu Organisasi</p>
            </div>
            <a href="{{ route('organisasi.dashboard') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs {{ $this->isActiveClass('organisasi.dashboard') }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('organisasi.pengumuman.index') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs {{ $this->isActiveClass('organisasi.pengumuman.index') }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                <span>Pengumuman</span>
            </a>
            <a href="{{ route('organisasi.kegiatan.index') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs {{ $this->isActiveClass('organisasi.kegiatan.index') }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span>Kegiatan</span>
            </a>
            <a href="{{ route('organisasi.proker.index') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs {{ $this->isActiveClass('organisasi.proker.index') }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                <span>Program Kerja</span>
            </a>
        @endif

        <!-- MAHASISWA MENU -->
        @role('mahasiswa')
            <div class="px-3 pt-2 pb-1 flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Menu Mahasiswa</p>
            </div>
            <a href="{{ route('mahasiswa.dashboard') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs {{ $this->isActiveClass('mahasiswa.dashboard') }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('mahasiswa.pengaduan.buat') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs {{ $this->isActiveClass('mahasiswa.pengaduan.buat') }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                <span>Buat Pengaduan</span>
            </a>
            <a href="{{ route('mahasiswa.pengaduan.index') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs {{ $this->isActiveClass('mahasiswa.pengaduan.index') }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Pengaduan Saya</span>
            </a>
        @endrole

    </nav>

    <!-- Bottom Actions (Profil & Logout) -->
    <div class="p-3 border-t border-white/[0.08] flex-shrink-0 bg-white/[0.01] space-y-1">
        <a href="{{ route('profile') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/[0.08] transition-all text-slate-400 hover:text-white text-xs font-semibold {{ $this->isActiveClass('profile') }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            <span>Profil Saya</span>
        </a>
        <button wire:click="logout" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-rose-500/15 transition-all text-slate-400 hover:text-rose-400 text-xs font-semibold group">
            <svg class="w-4 h-4 flex-shrink-0 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            <span>Keluar</span>
        </button>
    </div>
</div>
