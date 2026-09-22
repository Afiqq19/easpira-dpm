<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $nama = '';
    public string $email = '';
    public string $jabatan = '';

    public function mount(): void
    {
        $user = Auth::user();
        $this->nama = $user->nama ?? $user->name ?? '';
        $this->email = $user->email ?? '';
        // Kita simpan jabatan di field prodi agar tidak perlu tambah kolom db baru
        $this->jabatan = $user->prodi ?? '';
    }

    public function updateProfileInformation()
    {
        $user = Auth::user();

        $this->validate([
            'nama'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'jabatan' => ['nullable', 'string', 'max:100'],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
        ]);

        $user->nama = $this->nama;
        $user->name = $this->nama;
        $user->prodi = $this->jabatan; // Simpan jabatan ke field prodi
        
        // Eksekutif tidak memiliki NIM
        $user->nim = null;
        
        $user->email = $this->email;
        $user->save();

        $this->dispatch('profile-updated');
        session()->flash('success_profile', 'Data profil Pimpinan berhasil disimpan!');
        return redirect()->route('profile');
    }
}; ?>

<div class="space-y-6">
    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
        <div>
            <h3 class="text-base font-bold text-slate-800">Biodata & Jabatan Eksekutif</h3>
            <p class="text-xs text-slate-500 mt-0.5">
                Perbarui nama lengkap, email, dan jabatan struktural Anda.
            </p>
        </div>
        <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
        </div>
    </div>

    @if (session()->has('success_profile'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl flex items-center gap-3 text-xs font-bold shadow-sm">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success_profile') }}
        </div>
    @endif

    <form wire:submit="updateProfileInformation" class="space-y-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Nama Lengkap -->
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <input wire:model="nama" type="text" class="w-full rounded-2xl border-slate-200 bg-white/80 text-sm px-4 py-2.5 pl-10 focus:border-indigo-500 focus:ring-indigo-500 text-slate-800 font-medium" placeholder="Nama Lengkap Anda">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                @error('nama') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
            </div>

            <!-- Email Akun -->
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Email Akun
                </label>
                <div class="relative">
                    <input wire:model="email" type="email" class="w-full rounded-2xl border-slate-200 bg-slate-50 text-sm px-4 py-2.5 pl-10 focus:border-indigo-500 focus:ring-indigo-500 text-slate-700 font-medium cursor-not-allowed" readonly>
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                @error('email') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
            </div>

            <!-- Jabatan Eksekutif -->
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Jabatan Struktural
                </label>
                <div class="relative">
                    <input wire:model="jabatan" type="text" class="w-full rounded-2xl border-slate-200 bg-white/80 text-sm px-4 py-2.5 pl-10 focus:border-indigo-500 focus:ring-indigo-500 text-slate-800 font-medium" placeholder="Contoh: Direktur Utama / Wakil Direktur">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                @error('jabatan') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl font-bold text-xs shadow-md shadow-indigo-500/20 transition-all flex items-center gap-2">
                <span wire:loading.remove wire:target="updateProfileInformation" class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Perubahan
                </span>
                <span wire:loading wire:target="updateProfileInformation" class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Menyimpan...
                </span>
            </button>
        </div>
    </form>
</div>
