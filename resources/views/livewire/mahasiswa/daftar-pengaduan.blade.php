<div>
    <x-slot name="header">
        <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-sm font-medium mr-3">TIKET SAYA</span>
        Daftar Pengaduan
    </x-slot>

    @if (session()->has('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-4 rounded-xl flex items-center gap-3 animate-fade-in shadow-sm shadow-emerald-500/10">
            <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-bold text-lg">{{ session('success') }}</span>
        </div>
    @endif

    <div class="flex flex-col lg:grid lg:grid-cols-3 gap-6 lg:gap-8">
        <!-- List Pengaduan -->
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-slate-800">Riwayat Pengaduan Anda</h3>
                <a href="{{ route('mahasiswa.pengaduan.buat') }}" wire:navigate class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-md shadow-indigo-500/30 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Buat Baru
                </a>
            </div>

            @forelse($pengaduanUmum as $p)
                <div class="glass p-6 rounded-2xl hover:shadow-lg hover:shadow-indigo-500/10 transition-all border border-slate-100 relative overflow-hidden group">
                    <div class="flex flex-col sm:flex-row justify-between items-start gap-2 mb-4">
                        <div>
                            <div class="flex items-center gap-2 mb-1 flex-wrap">
                                <span class="font-mono text-sm font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">{{ $p->ticket_code }}</span>
                                <span class="text-xs text-slate-400">{{ $p->created_at->format('d M Y, H:i') }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $p->mode_privasi === 'anonim' ? 'bg-slate-800 text-white' : 'bg-emerald-100 text-emerald-700' }}">
                                    {{ $p->mode_privasi === 'anonim' ? 'Anonim' : 'Umum' }}
                                </span>
                            </div>
                            <span class="inline-block mt-1 px-3 py-1 bg-slate-100 text-slate-700 text-xs font-semibold rounded-full">
                                {{ $p->kategori->nama_kategori ?? 'Umum' }}
                            </span>
                        </div>
                        
                        @php
                            $statusColors = [
                                'diterima' => 'bg-slate-100 text-slate-700',
                                'diverifikasi' => 'bg-amber-100 text-amber-700',
                                'diproses' => 'bg-blue-100 text-blue-700',
                                'ditindaklanjuti' => 'bg-indigo-100 text-indigo-700',
                                'selesai' => 'bg-emerald-100 text-emerald-700',
                                'ditolak' => 'bg-rose-100 text-rose-700',
                            ];
                            $color = $statusColors[$p->status] ?? 'bg-slate-100 text-slate-700';
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $color }}">
                            {{ $p->status }}
                        </span>
                    </div>
                    
                    <p class="text-slate-700 text-sm line-clamp-2 mt-4">{{ $p->isi }}</p>
                    
                    <div class="mt-4 pt-4 border-t border-slate-100 flex justify-end">
                        <a href="{{ route('mahasiswa.pengaduan.detail', $p->ticket_code) }}" wire:navigate class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
                            Lihat Detail & Tanggapan
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="glass p-10 rounded-2xl text-center flex flex-col items-center justify-center border-dashed border-2 border-slate-200">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h4 class="text-lg font-bold text-slate-800 mb-2">Belum ada pengaduan</h4>
                    <p class="text-slate-500 text-sm max-w-md">Anda belum pernah membuat pengaduan. Semua pengaduan Anda, baik umum maupun anonim, akan tercatat dengan aman di sini.</p>
                </div>
            @endforelse
            
            <div class="mt-4">
                {{ $pengaduanUmum->links() }}
            </div>
        </div>

        <!-- Lacak Tiket Widget -->
        <div>
            <div class="glass p-6 rounded-2xl bg-gradient-to-br from-slate-800 to-slate-900 text-white shadow-xl">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold">Lacak Tiket Anonim</h3>
                </div>
                <p class="text-slate-400 text-sm mb-6">Punya nomor tiket dari pengaduan mode anonim? Masukkan di bawah ini untuk melihat statusnya.</p>
                <form wire:submit="lacakAnonim" class="space-y-4">
                    <div>
                        <input wire:model="searchTicketCode" type="text" class="w-full bg-slate-950/50 border border-slate-700 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-white placeholder-slate-500 text-sm uppercase" placeholder="Contoh: PLP-2026-ABCD">
                        @error('searchTicketCode') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl shadow-lg transition-colors flex justify-center items-center gap-2">
                        <span wire:loading.remove wire:target="lacakAnonim">Lacak Status</span>
                        <span wire:loading.flex wire:target="lacakAnonim" class="items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Mencari...
                        </span>
                    </button>
                </form>
            </div>
            
            <div class="mt-6 glass p-6 rounded-2xl">
                <h4 class="font-bold text-slate-800 mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    Privasi & Keamanan Terjamin
                </h4>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Semua laporan Anda (termasuk mode <strong>Anonim</strong>) tersimpan aman di akun Anda dan dapat Anda pantau setiap saat tanpa khawatir kehilangan nomor tiket. Identitas Anda untuk tiket anonim tetap 100% dirahasiakan dan dienkripsi dari staf biasa.
                </p>
            </div>
        </div>
    </div>
</div>


