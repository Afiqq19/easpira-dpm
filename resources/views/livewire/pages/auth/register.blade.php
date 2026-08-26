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

    <form wire:submit="register">
        <!-- NIM -->
        <div>
            <x-input-label for="nim" :value="__('Nomor Induk Mahasiswa (NIM)')" class="text-slate-700 font-medium" />
            <x-text-input wire:model="nim" id="nim" class="block mt-1 w-full border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/50 text-sm py-2.5" type="text" name="nim" required autofocus placeholder="Contoh: 2205012001" />
            <x-input-error :messages="$errors->get('nim')" class="mt-2 text-rose-500" />
        </div>

        <!-- Program Studi (Prodi) -->
        <div class="mt-4">
            <x-input-label for="prodi" :value="__('Program Studi (Prodi)')" class="text-slate-700 font-medium" />
            <x-text-input wire:model="prodi" id="prodi" class="block mt-1 w-full border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/50 text-sm py-2.5" type="text" name="prodi" required placeholder="Contoh: Teknik Komputer" />
            <x-input-error :messages="$errors->get('prodi')" class="mt-2 text-rose-500" />
        </div>

        <!-- Nama Lengkap -->
        <div class="mt-4">
            <x-input-label for="nama" :value="__('Nama Lengkap')" class="text-slate-700 font-medium" />
            <x-text-input wire:model="nama" id="nama" class="block mt-1 w-full border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/50 text-sm py-2.5" type="text" name="nama" required placeholder="Nama Lengkap Anda" />
            <x-input-error :messages="$errors->get('nama')" class="mt-2 text-rose-500" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email Mahasiswa')" class="text-slate-700 font-medium" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/50 text-sm py-2.5" type="email" name="email" required autocomplete="username" placeholder="email@students.polmed.ac.id" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-rose-500" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="text-slate-700 font-medium" />
            <x-text-input wire:model="password" id="password" class="block mt-1 w-full border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/50 text-sm py-2.5"
                            type="password"
                            name="password"
                            required autocomplete="new-password" placeholder="********" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-rose-500" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="text-slate-700 font-medium" />
            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white/50 text-sm py-2.5"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" placeholder="********" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-rose-500" />
        </div>

        <div class="flex items-center justify-between mt-8">
            <a class="text-sm font-semibold text-slate-500 hover:text-indigo-600 transition-colors inline-flex items-center gap-1.5" href="{{ route('login') }}" wire:navigate>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Login
            </a>

            <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 transition duration-150 ease-in-out shadow-md shadow-indigo-500/30 min-w-[120px] justify-center">
                <span wire:loading.remove wire:target="register">{{ __('Daftar Sekarang') }}</span>
                <span wire:loading.flex wire:target="register" class="items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Mendaftar...
                </span>
            </x-primary-button>
        </div>
        
        <div class="mt-6 flex items-center before:mt-0.5 before:flex-1 before:border-t before:border-slate-300 after:mt-0.5 after:flex-1 after:border-t after:border-slate-300">
            <p class="mx-4 mb-0 text-center text-sm font-semibold text-slate-500 uppercase">Atau Daftar Cepat</p>
        </div>

        <div class="mt-6">
            <a href="{{ route('google.login') }}" class="w-full flex items-center justify-center gap-3 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-semibold py-2.5 px-4 rounded-xl shadow-sm transition-all duration-150 hover:shadow-md">
                <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                Masuk dengan Akun Google
            </a>
        </div>
    </form>
</div>

