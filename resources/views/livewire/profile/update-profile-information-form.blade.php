<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $nama = '';
    public string $email = '';
    public string $nim = '';
    public string $prodi = '';

    public function mount(): void
    {
        $user = Auth::user();
        $this->nama = $user->nama ?? $user->name ?? '';
        $this->email = $user->email ?? '';
        $this->nim = $user->nim ?? '';
        $this->prodi = $user->prodi ?? '';
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $this->validate([
            'nama'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'nim'   => ['nullable', 'string', 'max:30'],
            'prodi' => ['nullable', 'string', 'max:100'],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
        ]);

        $user->nama = $this->nama;
        $user->name = $this->nama;
        
        // Simpan NIM & Prodi jika role mahasiswa
        if ($user->hasRole('mahasiswa')) {
            $user->nim = $this->nim;
            $user->prodi = $this->prodi;
        }

        $user->email = $this->email;
        $user->save();

        $this->dispatch('profile-updated');
        session()->flash('success_profile', 'Data profil Anda berhasil disimpan!');
    }
}; ?>

<div class="space-y-6">
    @php
        $user = Auth::user();
        $isMahasiswa = $user->hasRole('mahasiswa');
        $isOrganisasi = $user->hasAnyRole(['hmps', 'ukm']);
    @endphp

    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
        <div>
            <h3 class="text-base font-bold text-slate-800">Biodata & Informasi Akun</h3>
            <p class="text-xs text-slate-500 mt-0.5">
                @if($isMahasiswa)
                    Perbarui nama lengkap, NIM, dan program studi Anda.
                @else
                    Perbarui nama lengkap dan informasi akun Anda.
                @endif
            </p>
        </div>
        <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
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

            <!-- Email Kampus / Akun -->
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Email Akun
                </label>
                <div class="relative">
                    <input wire:model="email" type="email" class="w-full rounded-2xl border-slate-200 bg-slate-50 text-sm px-4 py-2.5 pl-10 focus:border-indigo-500 focus:ring-indigo-500 text-slate-700 font-medium cursor-not-allowed" readonly>
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    @if(str_contains(Auth::user()->email, '@students.polmed.ac.id') || Auth::user()->google_id)
                        <span class="absolute right-3 top-2.5 inline-flex items-center gap-1 text-[10px] font-bold bg-emerald-100 text-emerald-700 px-2.5 py-0.5 rounded-full">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            Google SSO
                        </span>
                    @endif
                </div>
                @error('email') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
            </div>

            <!-- NIM & Prodi (Hanya Ditampilkan untuk Mahasiswa) -->
            @if($isMahasiswa)
                <!-- NIM -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nomor Induk Mahasiswa (NIM)
                    </label>
                    <div class="relative">
                        <input wire:model="nim" type="text" class="w-full rounded-2xl border-slate-200 bg-white/80 text-sm px-4 py-2.5 pl-10 focus:border-indigo-500 focus:ring-indigo-500 text-slate-800 font-medium" placeholder="Contoh: 2205011001">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                    </div>
                    @error('nim') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Program Studi -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Program Studi / Jurusan
                    </label>
                    <div class="relative">
                        <input wire:model="prodi" type="text" class="w-full rounded-2xl border-slate-200 bg-white/80 text-sm px-4 py-2.5 pl-10 focus:border-indigo-500 focus:ring-indigo-500 text-slate-800 font-medium" placeholder="Contoh: Teknik Komputer / TI">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    @error('prodi') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                </div>
            @endif

            <!-- Organisasi -->
            @if($isOrganisasi && $user->organisasi)
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Organisasi
                    </label>
                    <div class="relative">
                        <input type="text" disabled value="{{ $user->organisasi->nama }}" class="w-full rounded-2xl border-slate-200 bg-slate-100 text-sm px-4 py-2.5 pl-10 text-slate-500 font-medium cursor-not-allowed">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                </div>
            @endif
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
