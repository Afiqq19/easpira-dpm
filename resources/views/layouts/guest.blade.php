<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'e-Aspira') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/icon_dpm.png') }}">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-800">
        
        <!-- Background Effects -->
        <div class="fixed inset-0 w-full h-full -z-10 pointer-events-none overflow-hidden">
            <div class="absolute top-0 right-0 w-full md:w-2/3 h-[500px] bg-brand-300 rounded-full blur-[100px] opacity-30 animate-blob mix-blend-multiply"></div>
            <div class="absolute -bottom-32 -left-32 w-[600px] h-[600px] bg-violet-300 rounded-full blur-[120px] opacity-30 animate-blob mix-blend-multiply" style="animation-delay: 2s"></div>
        </div>

        <div class="min-h-screen flex flex-col justify-center items-center py-10 px-4 sm:px-6">
            <div class="mb-6 text-center animate-slide-up">
                <a href="/" wire:navigate class="inline-flex flex-col items-center gap-3 group">
                    <img src="{{ asset('images/icon_dpm.png') }}" class="w-20 h-20 sm:w-24 sm:h-24 object-contain drop-shadow-md group-hover:-translate-y-1 transition-transform duration-300" alt="Logo DPM Polmed">
                    <span class="font-heading font-black text-3xl sm:text-4xl tracking-tight text-slate-800 group-hover:text-indigo-600 transition-colors">e-Aspira <span class="text-indigo-600">DPM</span></span>
                </a>
            </div>

            <div class="w-full sm:max-w-md px-6 py-8 sm:px-8 glass shadow-xl shadow-indigo-500/10 rounded-3xl border border-white/60 animate-slide-up" style="animation-delay: 0.1s;">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
