<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                @if($selectedOrganisasi)
                    <button wire:click="kembaliKeOrganisasi" class="p-2 rounded-xl bg-white hover:bg-slate-50 text-slate-500 hover:text-indigo-600 transition-all shadow-sm border border-slate-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                @endif
                <div>
                    <h2 class="text-2xl font-bold text-slate-800 tracking-tight">
                        @if($selectedOrganisasi)
                            Proker {{ $selectedOrganisasiNama }}
                        @else
                            Pantau Proker Organisasi
                        @endif
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">
                        @if($selectedOrganisasi)
                            Daftar program kerja yang terdaftar di organisasi ini.
                        @else
                            Pilih organisasi untuk melihat daftar program kerjanya.
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <select wire:model.live="periode_id" class="px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                <option value="">-- Pilih Periode --</option>
                @foreach($periodes as $periode)
                    <option value="{{ $periode->id }}">{{ $periode->nama }} {{ $periode->is_active ? '(Aktif)' : '' }}</option>
                @endforeach
            </select>
        </div>
    </div>

    @if(!$selectedOrganisasi)
        <!-- Search Organisasi -->
        <div class="bg-white/60 backdrop-blur-md p-4 rounded-2xl shadow-sm border border-slate-100 mb-6">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <input wire:model.live.debounce.300ms="searchOrg" type="text" class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-xl leading-5 bg-white/50 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-colors" placeholder="Cari organisasi (nama / singkatan)...">
            </div>
        </div>

        <!-- Grid Organisasi -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($organisasis as $org)
                <button wire:click="pilihOrganisasi({{ $org->id }})" class="text-left bg-white rounded-2xl p-5 shadow-sm border border-slate-200 hover:border-indigo-300 hover:shadow-md hover:bg-indigo-50/30 transition-all duration-200 group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-bold text-lg shrink-0 shadow-inner
                            {{ $org->tipe === 'HMPS' ? 'bg-indigo-100 text-indigo-600' : ($org->tipe === 'UKM' ? 'bg-amber-100 text-amber-600' : 'bg-emerald-100 text-emerald-600') }}">
                            {{ strtoupper(substr($org->singkatan ?? $org->nama, 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-slate-800 text-sm truncate group-hover:text-indigo-600 transition-colors">{{ $org->singkatan ?? $org->nama }}</h3>
                            <p class="text-xs text-slate-500 truncate">{{ $org->nama }}</p>
                            <div class="flex items-center gap-2 mt-1.5">
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full
                                    {{ $org->tipe === 'HMPS' ? 'bg-indigo-50 text-indigo-600' : ($org->tipe === 'UKM' ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600') }}">
                                    {{ $org->tipe }}
                                </span>
                                <span class="text-[10px] font-bold text-slate-400">{{ $org->program_kerja_count }} Proker</span>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-slate-300 group-hover:text-indigo-500 group-hover:translate-x-1 transition-all shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </button>
            @empty
                <div class="col-span-full text-center py-12 text-slate-400">
                    <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <p class="font-medium">Belum ada organisasi terdaftar.</p>
                </div>
            @endforelse
        </div>
    @else
        <!-- Filter & Search -->
        <div class="bg-white/60 backdrop-blur-md p-4 rounded-2xl shadow-sm border border-slate-100 flex flex-col sm:flex-row gap-4">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-xl leading-5 bg-white/50 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-colors" placeholder="Cari program kerja...">
            </div>
            <select wire:model.live="statusFilter" class="border border-slate-200 rounded-xl text-sm px-3 py-2 bg-white/50 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Semua Status</option>
                <option value="rencana">Rencana</option>
                <option value="berjalan">Berjalan</option>
                <option value="selesai">Selesai</option>
                <option value="dibatalkan">Dibatalkan</option>
            </select>
        </div>

        <!-- Tabel Proker (Read Only) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-full">Nama Program</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kategori</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Pelaksanaan</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($prokers as $proker)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-900">{{ $proker->nama }}</div>
                                    @if($proker->deskripsi)
                                        <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $proker->deskripsi }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                        {{ $proker->kategori === 'lainnya' && $proker->kategori_lainnya ? $proker->kategori_lainnya : $proker->kategori }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                    @if($proker->tanggal_mulai)
                                        {{ $proker->tanggal_mulai->format('d M Y') }}
                                        @if($proker->tanggal_selesai && $proker->tanggal_mulai != $proker->tanggal_selesai)
                                            - {{ $proker->tanggal_selesai->format('d M Y') }}
                                        @endif
                                    @else
                                        <span class="text-slate-400 italic">Belum ditentukan</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-xs font-semibold rounded-full px-3 py-1
                                        {{ $proker->status === 'rencana' ? 'bg-slate-100 text-slate-700' : '' }}
                                        {{ $proker->status === 'berjalan' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $proker->status === 'selesai' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                        {{ $proker->status === 'dibatalkan' ? 'bg-rose-100 text-rose-700' : '' }}">
                                        {{ ucfirst($proker->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        </div>
                                        <h3 class="text-sm font-medium text-slate-900">Belum ada Program Kerja</h3>
                                        <p class="mt-1 text-sm text-slate-500">Organisasi ini belum mendaftarkan program kerja.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($prokers && $prokers->hasPages())
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $prokers->links() }}
                </div>
            @endif
        </div>
    @endif
</div>
