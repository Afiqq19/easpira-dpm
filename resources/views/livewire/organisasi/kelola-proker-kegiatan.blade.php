<div x-data="{ openProker: null }">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Program Kerja & Kegiatan</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola program kerja dan kegiatan organisasi Anda.</p>
        </div>
        <div class="flex items-center gap-3">
            <select wire:model.live="periode_id" class="px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                <option value="">-- Pilih Periode --</option>
                @foreach($periodes as $periode)
                    <option value="{{ $periode->id }}">{{ $periode->nama }} {{ $periode->is_active ? '(Aktif)' : '' }}</option>
                @endforeach
            </select>
            @if(!$isReadOnly)
                <button wire:click="createProker" class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-indigo-700 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Program Kerja
                </button>
            @endif
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 bg-emerald-50 text-emerald-600 p-4 rounded-xl flex items-center gap-3 border border-emerald-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            {{ session('message') }}
        </div>
    @endif

    @if($isReadOnly)
        <div class="mb-4 bg-amber-50 text-amber-700 p-4 rounded-xl flex items-center gap-3 border border-amber-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            Anda sedang melihat data periode lalu. Data hanya dapat dilihat dan tidak dapat diubah (Read-Only).
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6">
        <div class="p-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
            <div class="relative w-full max-w-md">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari program kerja atau kegiatan..." class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all bg-white">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse ($prokers as $proker)
                <div class="p-4 transition-colors hover:bg-slate-50">
                    <div class="flex items-start justify-between cursor-pointer" @click="openProker === {{ $proker->id }} ? openProker = null : openProker = {{ $proker->id }}">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="text-base font-bold text-slate-800">{{ $proker->nama }}</h3>
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200 capitalize">
                                    {{ $proker->kategori }}
                                </span>
                                @if($proker->status == 'selesai')
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 border border-emerald-200">Selesai</span>
                                @elseif($proker->status == 'berjalan')
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700 border border-indigo-200">Berjalan</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700 border border-amber-200 capitalize">{{ $proker->status }}</span>
                                @endif
                            </div>
                            <p class="text-sm text-slate-500 line-clamp-2">{{ $proker->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                            <div class="mt-2 text-xs text-slate-400 flex gap-4">
                                <span><i class="fas fa-calendar mr-1"></i> {{ $proker->tanggal_mulai ? \Carbon\Carbon::parse($proker->tanggal_mulai)->translatedFormat('d M Y') : '-' }} s/d {{ $proker->tanggal_selesai ? \Carbon\Carbon::parse($proker->tanggal_selesai)->translatedFormat('d M Y') : '-' }}</span>
                                <span><i class="fas fa-list mr-1"></i> {{ $proker->kegiatan->count() }} Kegiatan</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if(!$isReadOnly)
                                <button wire:click.stop="createKegiatan({{ $proker->id }})" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors text-xs font-medium border border-indigo-100" title="Tambah Kegiatan">
                                    + Kegiatan
                                </button>
                                <button wire:click.stop="editProker({{ $proker->id }})" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit Proker">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <button wire:click.stop="confirmDelete('proker', {{ $proker->id }})" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Proker">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            @endif
                            <svg class="w-5 h-5 text-slate-400 transform transition-transform" :class="openProker === {{ $proker->id }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    
                    <!-- Dropdown Kegiatan -->
                    <div x-show="openProker === {{ $proker->id }}" x-collapse class="mt-4 pt-4 border-t border-slate-100 pl-4 border-l-2 border-indigo-100 ml-2">
                        @if($proker->kegiatan->count() > 0)
                            <div class="space-y-3">
                                @foreach($proker->kegiatan as $kegiatan)
                                    <div class="flex items-center justify-between p-3 bg-white border border-slate-100 rounded-xl hover:border-slate-200 transition-colors shadow-sm">
                                        <div>
                                            <h4 class="text-sm font-bold text-slate-800">{{ $kegiatan->judul }}</h4>
                                            <p class="text-xs text-slate-500 mt-0.5">
                                                <i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->translatedFormat('d M Y H:i') }} | 
                                                <i class="fas fa-map-marker-alt mr-1"></i> {{ $kegiatan->lokasi }}
                                            </p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            @if(!$isReadOnly)
                                                <button wire:click.stop="editKegiatan({{ $kegiatan->id }})" class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-colors" title="Edit Kegiatan">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                </button>
                                                <button wire:click.stop="confirmDelete('kegiatan', {{ $kegiatan->id }})" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors" title="Hapus Kegiatan">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-slate-500 italic">Belum ada kegiatan untuk program kerja ini.</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="px-6 py-8 text-center text-slate-500">
                    <div class="flex flex-col items-center justify-center">
                        <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        <p>Belum ada program kerja yang ditambahkan pada periode ini.</p>
                    </div>
                </div>
            @endforelse
        </div>
        
        @if($prokers->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $prokers->links(data: ['scrollTo' => false]) }}
            </div>
        @endif
    </div>

    <!-- Modal Proker -->
    @if($isProkerModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" wire:click="$set('isProkerModalOpen', false)"></div>
            <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 p-6 max-h-[90vh] overflow-y-auto">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-slate-800">{{ $proker_id ? 'Edit Program Kerja' : 'Tambah Program Kerja' }}</h3>
                </div>
                
                <form wire:submit="saveProker">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Program Kerja</label>
                            <input type="text" wire:model="nama" class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 text-sm">
                            @error('nama') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
                            <textarea wire:model="deskripsi" rows="3" class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 text-sm"></textarea>
                            @error('deskripsi') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Mulai</label>
                                <input type="date" wire:model="tanggal_mulai" class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 text-sm">
                                @error('tanggal_mulai') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Selesai</label>
                                <input type="date" wire:model="tanggal_selesai" class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 text-sm">
                                @error('tanggal_selesai') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Kategori</label>
                                <select wire:model.live="kategori" class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 text-sm">
                                    <option value="akademik">Akademik</option>
                                    <option value="sosial">Sosial</option>
                                    <option value="olahraga">Olahraga</option>
                                    <option value="seni">Seni</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                                @error('kategori') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                                <select wire:model="status" class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 text-sm">
                                    <option value="rencana">Rencana</option>
                                    <option value="berjalan">Berjalan</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="dibatalkan">Dibatalkan</option>
                                </select>
                                @error('status') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        @if($kategori === 'lainnya')
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Sebutkan Kategori</label>
                                <input type="text" wire:model="kategori_lainnya" class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 text-sm">
                                @error('kategori_lainnya') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-100">
                        <button type="button" wire:click="$set('isProkerModalOpen', false)" class="px-4 py-2 rounded-xl text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded-xl text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Kegiatan -->
    @if($isKegiatanModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" wire:click="$set('isKegiatanModalOpen', false)"></div>
            <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 p-6 max-h-[90vh] overflow-y-auto">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-slate-800">{{ $kegiatan_id ? 'Edit Kegiatan' : 'Tambah Kegiatan' }}</h3>
                </div>
                
                <form wire:submit="saveKegiatan">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Judul Kegiatan</label>
                            <input type="text" wire:model="judul" class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 text-sm">
                            @error('judul') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
                            <textarea wire:model="deskripsi_kegiatan" rows="3" class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 text-sm"></textarea>
                            @error('deskripsi_kegiatan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Waktu Mulai</label>
                                <input type="datetime-local" wire:model="tgl_mulai_kegiatan" class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 text-sm">
                                @error('tgl_mulai_kegiatan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Waktu Selesai</label>
                                <input type="datetime-local" wire:model="tgl_selesai_kegiatan" class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 text-sm">
                                @error('tgl_selesai_kegiatan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Lokasi</label>
                            <input type="text" wire:model="lokasi" class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 text-sm">
                            @error('lokasi') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-100">
                        <button type="button" wire:click="$set('isKegiatanModalOpen', false)" class="px-4 py-2 rounded-xl text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded-xl text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Delete Modal -->
    @if($isDeleteModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" wire:click="$set('isDeleteModalOpen', false)"></div>
            <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6 text-center overflow-hidden">
                <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Hapus {{ ucfirst($deleteType) }}?</h3>
                <p class="text-slate-500 text-sm mb-6">Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>
                <div class="flex justify-center gap-3">
                    <button wire:click="$set('isDeleteModalOpen', false)" class="px-4 py-2 rounded-xl text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">Batal</button>
                    <button wire:click="delete" class="px-4 py-2 rounded-xl text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition-colors">Ya, Hapus</button>
                </div>
            </div>
        </div>
    @endif
</div>
