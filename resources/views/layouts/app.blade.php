<!DOCTYPE html>
<html lang="id" class="notranslate" translate="no">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google" content="notranslate">`r`n        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'e-Aspira DPM Polmed') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/icon_dpm.png') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>[x-cloak] { display: none !important; }</style>
    </head>
    <body class="font-sans antialiased text-slate-800 bg-slate-50" 
          x-data="{ sidebarOpen: false }" 
          @keydown.escape.window="sidebarOpen = false"
          x-on:livewire:navigated.window="sidebarOpen = false">
        
        <div class="flex h-screen overflow-hidden">

            <!-- Mobile overlay -->
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 @click="sidebarOpen = false" 
                 class="fixed inset-0 z-30 bg-slate-900/60 backdrop-blur-sm md:hidden" 
                 style="display: none;"></div>

            <!-- Sidebar -->
            <livewire:layout.sidebar />

            <!-- Main Content -->
            <div class="flex-1 flex flex-col h-screen overflow-hidden bg-slate-50 relative w-full">
                
                <!-- Blob Background Effect -->
                <div class="absolute top-0 left-0 w-full h-96 overflow-hidden -z-10 pointer-events-none opacity-30">
                    <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-indigo-300 blur-3xl"></div>
                    <div class="absolute top-12 right-24 w-80 h-80 rounded-full bg-violet-300 blur-3xl" style="animation-delay: 2s;"></div>
                </div>

                <!-- Header -->
                <header class="h-14 sm:h-16 bg-white/80 backdrop-blur-xl border-b border-slate-200/80 sticky top-0 z-20 px-4 sm:px-6 lg:px-8 flex items-center justify-between shadow-sm flex-shrink-0">
                    <div class="flex items-center flex-1 gap-3">
                        <!-- Hamburger Menu (Mobile Only) -->
                        <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 -ml-1 rounded-xl text-slate-500 hover:bg-slate-100 hover:text-indigo-600 focus:outline-none transition-colors">
                            <svg x-show="!sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                            <svg x-show="sidebarOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>

                        <!-- Mobile Logo -->
                        <div class="md:hidden flex items-center gap-2">
                            <img src="{{ asset('images/icon_dpm.png') }}" class="w-7 h-7 object-contain" alt="Logo">
                            <span class="font-heading font-bold text-slate-800 text-sm">e-Aspira <span class="text-indigo-600">DPM</span></span>
                        </div>

                        <!-- Search bar (Desktop only) -->
                        <form action="{{ route('dashboard.redirect') }}" method="GET" class="relative w-full max-w-md hidden md:block">
                            <button type="submit" class="absolute inset-y-0 left-0 pl-3 flex items-center cursor-pointer hover:text-indigo-600 transition-colors">
                                <svg class="h-5 w-5 text-slate-400 hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </button>
                            <input type="text" name="search" class="block w-full pl-10 pr-3 py-2 border-none rounded-xl bg-slate-100/80 text-slate-800 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all text-sm" placeholder="Lacak tiket (PLP-...)">
                        </form>
                    </div>
                    
                    <div class="flex items-center gap-2 sm:gap-4">
                        <!-- Profile Dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 focus:outline-none bg-slate-100 p-1 sm:p-1.5 rounded-full hover:bg-indigo-50 transition-colors border border-slate-200 hover:border-indigo-200">
                                <div class="h-8 w-8 rounded-full bg-gradient-to-r from-indigo-500 to-violet-500 flex items-center justify-center text-white font-bold text-sm shadow-inner">
                                    {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                                </div>
                                <span class="text-sm font-medium text-slate-700 hidden lg:block pr-1">{{ explode(' ', auth()->user()->nama)[0] }}</span>
                                <svg class="h-4 w-4 text-slate-400 hidden lg:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="open" x-transition class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-lg border border-slate-100 py-1 z-50 overflow-hidden" style="display: none;">
                                <div class="px-4 py-3 border-b border-slate-100">
                                    <p class="text-sm font-semibold text-slate-900 truncate">{{ auth()->user()->nama }}</p>
                                    <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="{{ route('profile') }}" wire:navigate class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    Profil Saya
                                </a>
                                <div class="border-t border-slate-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50 transition-colors text-left">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 z-0 relative pb-20">
                    
                    @if (isset($header))
                        <div class="mb-6 sm:mb-8">
                            <h1 class="text-xl sm:text-2xl font-heading font-bold text-slate-800 flex items-center gap-3">
                                {{ $header }}
                            </h1>
                        </div>
                    @endif

                    {{ $slot }}
                    
                </main>
            </div>
        </div>

        <!-- HEIC converter & SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/heic2any@0.0.4/dist/heic2any.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.directive('confirm', ({ el, directive, component, cleanup }) => {
                    let content = directive.expression;
                    let onClick = e => {
                        e.preventDefault();
                        e.stopPropagation();
                        Swal.fire({
                            title: 'Konfirmasi Tindakan',
                            text: content,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#4f46e5',
                            cancelButtonColor: '#ef4444',
                            confirmButtonText: 'Ya, Lanjutkan',
                            cancelButtonText: 'Batal',
                            customClass: {
                                popup: 'rounded-2xl shadow-2xl border border-slate-100',
                                title: 'font-bold text-slate-800',
                                htmlContainer: 'text-slate-600 text-sm mt-2',
                                confirmButton: 'px-5 py-2.5 rounded-xl font-bold shadow-md shadow-indigo-500/30',
                                cancelButton: 'px-5 py-2.5 rounded-xl font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 border-none'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                el.removeAttribute('wire:confirm');
                                el.click();
                                el.setAttribute('wire:confirm', content);
                            }
                        });
                    };
                    el.addEventListener('click', onClick, { capture: true });
                    cleanup(() => {
                        el.removeEventListener('click', onClick, { capture: true });
                    });
                });
            });
        </script>
    </body>
</html>





