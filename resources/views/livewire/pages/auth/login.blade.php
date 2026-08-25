<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        // Redirect based on role
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $this->redirectIntended(default: route('admin.dashboard', absolute: false), navigate: true);
        } elseif ($user->hasRole('staff_dewan')) {
            $this->redirectIntended(default: route('dewan.dashboard', absolute: false), navigate: true);
        } elseif ($user->hasRole('hmps') || $user->hasRole('ukm')) {
            $this->redirectIntended(default: route('organisasi.dashboard', absolute: false), navigate: true);
        } else {
            $this->redirectIntended(default: route('mahasiswa.dashboard', absolute: false), navigate: true);
        }
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if (request()->query('oauth_error') == 'not_polmed')
        <div class="mb-6 bg-rose-50 text-rose-600 px-4 py-3 rounded-xl border border-rose-200 shadow-sm font-medium flex items-start gap-3">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <div>
                <p class="text-sm font-bold leading-tight">Akses Ditolak: Email Bukan Domain Kampus</p>
                <p class="text-xs text-rose-500 mt-1 leading-relaxed">
                    Anda wajib login menggunakan email resmi Polmed (<strong class="text-rose-700 font-mono">@students.polmed.ac.id</strong> atau <strong class="text-rose-700 font-mono">@polmed.ac.id</strong>).
                    @if(request()->query('rejected_email'))
                        <br><span class="text-[11px] text-slate-500">Email yang dipilih: {{ request()->query('rejected_email') }}</span>
                    @endif
                </p>
            </div>
        </div>
    @elseif (session('error'))
        <div class="mb-6 bg-rose-50 text-rose-600 px-4 py-3 rounded-xl border border-rose-200 shadow-sm font-medium flex items-start gap-3">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <p class="text-sm leading-relaxed">{{ session('error') }}</p>
        </div>
    @endif

    <form wire:submit="login" class="space-y-5">
        <!-- Email / NIM -->
        <div>
            <x-input-label for="login" :value="__('Email / NIM / Username')" class="text-slate-700 font-semibold text-sm mb-1.5 block" />
            <x-text-input wire:model="form.login" id="login" class="block w-full border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/70 text-sm py-3 px-4" type="text" name="login" required autofocus autocomplete="username" placeholder="Masukkan Email atau NIM Anda" />
            <x-input-error :messages="$errors->get('form.login')" class="mt-2 text-rose-500 text-xs" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <x-input-label for="password" :value="__('Password')" class="text-slate-700 font-semibold text-sm" />
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" wire:navigate class="text-xs text-indigo-500 hover:text-indigo-700 font-medium transition-colors">
                        Lupa password?
                    </a>
                @endif
            </div>
            <x-text-input wire:model="form.password" id="password" class="block w-full border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/70 text-sm py-3 px-4" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('form.password')" class="mt-2 text-rose-500 text-xs" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
            <label for="remember" class="ms-2 text-sm text-slate-600 cursor-pointer select-none">Ingat saya</label>
        </div>

        <!-- Submit Button (Full Width) -->
        <button type="submit" class="w-full flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-bold py-3.5 px-6 rounded-xl shadow-lg shadow-indigo-500/30 transition-all duration-150 mt-2">
            <span wire:loading.remove wire:target="login" class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                MASUK
            </span>
            <span wire:loading.flex wire:target="login" class="items-center gap-2">
                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Memproses...
            </span>
        </button>

        <!-- Divider -->
        <div class="flex items-center gap-3 my-1">
            <div class="flex-1 h-px bg-slate-200"></div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Atau</p>
            <div class="flex-1 h-px bg-slate-200"></div>
        </div>

        <!-- Google Login -->
        <a href="{{ route('google.login') }}" class="w-full flex items-center justify-center gap-3 bg-white hover:bg-slate-50 active:bg-slate-100 border border-slate-200 text-slate-700 font-semibold py-3 px-4 rounded-xl shadow-sm transition-all duration-150 hover:shadow-md">
            <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
            <span class="text-sm">Masuk dengan Akun Google</span>
        </a>

        <!-- Bottom Links -->
        <div class="flex flex-col items-center gap-3 pt-1">
            <a href="{{ route('register') }}" wire:navigate class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                Belum punya akun? <span class="underline underline-offset-2">Daftar di sini</span>
            </a>
            <a href="/" wire:navigate class="inline-flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Beranda
            </a>
        </div>
    </form>
</div>

