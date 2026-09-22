<div x-cloak :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'" 
     class="fixed md:relative inset-y-0 left-0 w-72 md:w-64 flex flex-col h-screen z-40 
            transition-transform duration-300 ease-in-out
            bg-white/95 backdrop-blur-xl border-r border-slate-200/90 shadow-xl shadow-slate-200/40 shrink-0 select-none">

    <!-- Brand Logo & Header -->
    <div class="h-16 flex items-center justify-between px-5 border-b border-slate-100 flex-shrink-0 bg-slate-50/50">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-600 flex items-center justify-center p-1.5 shadow-md shadow-indigo-500/20 border border-indigo-200/50 flex-shrink-0">
                <img src="{{ asset('images/icon_dpm.png') }}" class="w-full h-full object-contain filter drop-shadow" alt="Logo DPM">
            </div>
            <div class="flex flex-col notranslate" translate="no">
                <span class="font-heading font-extrabold text-slate-800 text-base tracking-tight leading-none">
                    e-Aspira <span class="text-indigo-600">DPM</span>
                </span>
                <span class="text-[10px] text-slate-400 font-bold tracking-wider uppercase mt-1">DPM POLMED</span>
            </div>
        </div>
        <!-- Close button (Mobile Only) -->
        <button @click="sidebarOpen = false" class="md:hidden p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <!-- User Profile Widget (Pearl Glass Card) -->
    <div class="p-3.5 border-b border-slate-100 flex-shrink-0">
        <a href="{{ route('profile') }}" wire:navigate class="flex items-center gap-3 p-2.5 rounded-2xl bg-slate-50 hover:bg-indigo-50/60 border border-slate-200/80 hover:border-indigo-200 transition-all duration-200 group shadow-sm">
            <div class="relative flex-shrink-0">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-600 p-0.5 shadow-md shadow-indigo-500/25 ring-2 ring-white">
                    <div class="w-full h-full rounded-[10px] bg-indigo-50 flex items-center justify-center text-indigo-700 font-extrabold text-sm overflow-hidden">
                        @if(auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" class="w-full h-full object-cover" alt="Avatar">
                        @else
                            {{ strtoupper(substr(auth()->user()->nama ?? auth()->user()->name ?? 'U', 0, 1)) }}
                        @endif
                    </div>
                </div>
                <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-emerald-500 border-2 border-white" title="Aktif"></div>
            </div>
            <div class="overflow-hidden min-w-0 flex-1">
                <p class="font-bold text-slate-800 text-xs truncate group-hover:text-indigo-600 transition-colors">
                    {{ auth()->user()->nama ?? auth()->user()->name }}
                </p>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="text-[10px] font-bold text-indigo-600 capitalize truncate">
                        {{ auth()->user()->roles->first()?->name ?? 'Mahasiswa' }}
                    </span>
                    @if(auth()->user()->google_id)
                        <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                        <span class="text-[9px] font-bold text-emerald-600">SSO</span>
                    @endif
                </div>
            </div>
            <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-600 group-hover:translate-x-0.5 transition-all flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
    </div>

    <!-- Navigation Menu Items -->
    <nav class="flex-1 overflow-y-auto py-3 px-3 space-y-1">

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
            <div x-data="{ openMaster: {{ request()->routeIs('admin.users', 'admin.organisasi.index') ? 'true' : 'false' }} }">
                <button @click="openMaster = !openMaster" class="w-full flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-xl text-xs {{ $this->isActiveClass('admin.users') || $this->isActiveClass('admin.organisasi.index') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                        <span>Master Data</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="openMaster ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="openMaster" x-collapse class="pl-7 pr-2 py-1 space-y-1">
                    <a href="{{ route('admin.users') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2 rounded-lg text-xs {{ $this->isActiveClass('admin.users') }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.users') ? 'bg-indigo-600' : 'bg-slate-300' }}"></span>
                        <span>Data User</span>
                    </a>
                    <a href="{{ route('admin.organisasi.index') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2 rounded-lg text-xs {{ $this->isActiveClass('admin.organisasi.index') }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.organisasi.index') ? 'bg-indigo-600' : 'bg-slate-300' }}"></span>
                        <span>Data Organisasi</span>
                    </a>
                </div>
            </div>
            <a href="{{ route('admin.pengumuman.index') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs {{ $this->isActiveClass('admin.pengumuman.index') }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                <span>Pengumuman</span>
            </a>
            <a href="{{ route('admin.pengaduan.index') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs {{ $this->isActiveClass('admin.pengaduan.index') }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Data Pengaduan</span>
            </a>
            <a href="{{ route('admin.proker-kegiatan.index') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs {{ $this->isActiveClass('admin.proker-kegiatan.index') }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                <span>Proker & Kegiatan</span>
            </a>
            <div x-data="{ openSystem: {{ request()->routeIs('admin.periode.index', 'admin.uu-kema.index') ? 'true' : 'false' }} }">
                <button @click="openSystem = !openSystem" class="w-full flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-xl text-xs {{ $this->isActiveClass('admin.periode.index') || $this->isActiveClass('admin.uu-kema.index') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>Kelola Manajemen</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="openSystem ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="openSystem" x-collapse class="pl-7 pr-2 py-1 space-y-1">
                    <a href="{{ route('admin.periode.index') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2 rounded-lg text-xs {{ $this->isActiveClass('admin.periode.index') }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.periode.index') ? 'bg-indigo-600' : 'bg-slate-300' }}"></span>
                        <span>Kelola Periode</span>
                    </a>
                    <a href="{{ route('admin.uu-kema.index') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2 rounded-lg text-xs {{ $this->isActiveClass('admin.uu-kema.index') }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.uu-kema.index') ? 'bg-indigo-600' : 'bg-slate-300' }}"></span>
                        <span>Kelola UU Kema</span>
                    </a>
                </div>
            </div>
        @endrole

        <!-- EKSEKUTIF MENU -->
        @if(auth()->user()->isEksekutif())
            <div class="px-3 pt-2 pb-1 flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Menu Eksekutif</p>
            </div>
            <a href="{{ route('eksekutif.dashboard') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs {{ $this->isActiveClass('eksekutif.dashboard') }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('eksekutif.proker-kegiatan.index') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs {{ $this->isActiveClass('eksekutif.proker-kegiatan.index') }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                <span>Proker & Kegiatan</span>
            </a>
            <a href="{{ route('eksekutif.pengaduan.index') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs {{ $this->isActiveClass('eksekutif.pengaduan.index') }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Pantau Pengaduan</span>
            </a>
        @endif

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
            <a href="{{ route('dewan.proker-kegiatan.index') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs {{ $this->isActiveClass('dewan.proker-kegiatan.index') }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                <span>Proker & Kegiatan</span>
            </a>
            <a href="{{ route('dewan.evaluasi-proker.index') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs {{ $this->isActiveClass('dewan.evaluasi-proker.index') }}">
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
            <a href="{{ route('organisasi.proker-kegiatan.index') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs {{ $this->isActiveClass('organisasi.proker-kegiatan.index') }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                <span>Proker & Kegiatan</span>
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
            <a href="{{ route('mahasiswa.evaluasi-proker.index') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs {{ $this->isActiveClass('mahasiswa.evaluasi-proker.index') }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                <span>Beri Evaluasi Proker</span>
            </a>
        @endrole

    </nav>

    <!-- Bottom Actions (Profil & Logout) -->
    <div class="p-3 border-t border-slate-100 flex-shrink-0 bg-slate-50/50 space-y-1">
        <a href="{{ route('profile') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-indigo-50/80 transition-all text-slate-600 hover:text-indigo-600 text-xs font-semibold {{ $this->isActiveClass('profile') }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            <span>Profil Saya</span>
        </a>
        <button wire:click="logout" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-rose-50 transition-all text-slate-600 hover:text-rose-600 text-xs font-semibold group">
            <svg class="w-4 h-4 flex-shrink-0 group-hover:-translate-x-0.5 transition-transform text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            <span>Keluar</span>
        </button>
    </div>
</div>


