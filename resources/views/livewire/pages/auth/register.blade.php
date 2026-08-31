<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $nim = '';
    public string $prodi = '';
    public string $nama = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'nim' => ['required', 'string', 'max:20', 'unique:'.User::class],
            'prodi' => ['required', 'string', 'max:100'],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class, 'ends_with:@students.polmed.ac.id'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ], [
            'email.ends_with' => 'Anda wajib mendaftar menggunakan email kampus (@students.polmed.ac.id)',
        ]);

        $validated['name'] = $validated['nama']; // name field required by Laravel defaults
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);
        $user->assignRole('mahasiswa');

        event(new Registered($user));

        Auth::login($user);

        $this->redirect(route('mahasiswa.dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-slate-800">Daftar Akun Mahasiswa</h2>
        <p class="text-slate-500 text-sm mt-1">Silakan lengkapi data diri Anda di bawah ini</p>
    </div>

    <form wire:submit="register" class="space-y-4">
        <!-- NIM -->
        <div>
            <x-input-label for="nim" :value="__('Nomor Induk Mahasiswa (NIM)')" class="text-slate-700 font-semibold text-sm mb-1 block" />
            <input wire:model="nim" id="nim" class="block w-full border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/70 text-sm py-2.5 px-3.5 shadow-sm" type="text" name="nim" required autofocus placeholder="Contoh: 2205012001" />
            <x-input-error :messages="$errors->get('nim')" class="mt-1.5 text-rose-500 text-xs" />
        </div>

        <!-- Program Studi (Prodi) -->
        <div>
            <x-input-label for="prodi" :value="__('Program Studi (Prodi)')" class="text-slate-700 font-semibold text-sm mb-1 block" />
            <input wire:model="prodi" id="prodi" class="block w-full border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/70 text-sm py-2.5 px-3.5 shadow-sm" type="text" name="prodi" required placeholder="Contoh: Teknik Komputer" />
            <x-input-error :messages="$errors->get('prodi')" class="mt-1.5 text-rose-500 text-xs" />
        </div>

        <!-- Nama Lengkap -->
        <div>
            <x-input-label for="nama" :value="__('Nama Lengkap')" class="text-slate-700 font-semibold text-sm mb-1 block" />
            <input wire:model="nama" id="nama" class="block w-full border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/70 text-sm py-2.5 px-3.5 shadow-sm" type="text" name="nama" required placeholder="Nama Lengkap Anda" />
            <x-input-error :messages="$errors->get('nama')" class="mt-1.5 text-rose-500 text-xs" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Mahasiswa (Domain Kampus)')" class="text-slate-700 font-semibold text-sm mb-1 block" />
            <input wire:model="email" id="email" class="block w-full border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/70 text-sm py-2.5 px-3.5 shadow-sm" type="email" name="email" required autocomplete="username" placeholder="email@students.polmed.ac.id" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-rose-500 text-xs" />
        </div>

        <!-- Password -->
        <div x-data="{ showRegPw: false }">
            <x-input-label for="password" :value="__('Password')" class="text-slate-700 font-semibold text-sm mb-1 block" />
            <div class="relative flex items-center">
                <input wire:model="password" id="password" :type="showRegPw ? 'text' : 'password'" class="block w-full border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/70 text-sm py-2.5 pl-3.5 pr-11 shadow-sm font-medium text-slate-800" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
                <button type="button" @click="showRegPw = !showRegPw" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-indigo-600 focus:outline-none transition-colors" title="Lihat/Sembunyikan password">
                    <svg x-show="!showRegPw" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <svg x-show="showRegPw" x-cloak class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-rose-500 text-xs" />
        </div>

        <!-- Confirm Password -->
        <div x-data="{ showRegPwConf: false }">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="text-slate-700 font-semibold text-sm mb-1 block" />
            <div class="relative flex items-center">
                <input wire:model="password_confirmation" id="password_confirmation" :type="showRegPwConf ? 'text' : 'password'" class="block w-full border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/70 text-sm py-2.5 pl-3.5 pr-11 shadow-sm font-medium text-slate-800" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password Anda" />
                <button type="button" @click="showRegPwConf = !showRegPwConf" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-indigo-600 focus:outline-none transition-colors" title="Lihat/Sembunyikan password">
                    <svg x-show="!showRegPwConf" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <svg x-show="showRegPwConf" x-cloak class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5 text-rose-500 text-xs" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-indigo-500/30 transition-all duration-150">
                <span wire:loading.remove wire:target="register">{{ __('Daftar Sekarang') }}</span>
                <span wire:loading.flex wire:target="register" class="items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Mendaftar...
                </span>
            </button>
        </div>
        
        <div class="flex items-center gap-3 my-1 pt-1">
            <div class="flex-1 h-px bg-slate-200"></div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Atau</p>
            <div class="flex-1 h-px bg-slate-200"></div>
        </div>

        <div>
            <a href="{{ route('google.login') }}" class="w-full flex items-center justify-center gap-3 bg-white hover:bg-slate-50 active:bg-slate-100 border border-slate-200 text-slate-700 font-semibold py-3 px-4 rounded-xl shadow-sm transition-all duration-150 hover:shadow-md">
                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                <span class="text-sm">Masuk dengan Akun Google</span>
            </a>
        </div>

        <div class="flex flex-col items-center gap-2 pt-2 text-center">
            <a class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors" href="{{ route('login') }}" wire:navigate>
                Sudah punya akun? <span class="underline underline-offset-2">Masuk di sini</span>
            </a>
            <a href="/" wire:navigate class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Beranda
            </a>
        </div>
    </form>
</div>

