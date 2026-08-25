<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Agenda & Kegiatan</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola jadwal kegiatan dan acara yang diadakan oleh organisasi Anda.</p>
        </div>
        <button wire:click="create" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all shadow-md hover:shadow-lg shadow-indigo-500/30">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Buat Kegiatan
        </button>
    </div>

    <!-- Filter & Search Section -->
    <div class="bg-white/60 backdrop-blur-md p-4 rounded-2xl shadow-sm border border-slate-100 flex flex-col sm:flex-row gap-4">
        <div class="flex-1 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-xl leading-5 bg-white/50 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-colors" placeholder="Cari nama kegiatan atau lokasi...">
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl relative flex items-start shadow-sm animate-slide-up" role="alert">
            <svg class="w-5 h-5 text-emerald-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="block sm:inline text-sm font-medium">{{ session('message') }}</span>
        </div>
    @endif

    <!-- Data List -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($kegiatans as $item)
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow relative flex flex-col group">
                <!-- Status Badge -->
                <div class="absolute top-4 right-4 flex gap-2 z-10">
                    @if($item->is_published)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-500 text-white shadow-sm">Publik</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-slate-500 text-white shadow-sm">Draft</span>
                    @endif
                </div>

                <!-- Poster/Image Placeholder -->
                <div class="w-full h-40 bg-slate-100 relative overflow-hidden flex items-center justify-center">
                    @if($item->poster)
                        <img src="{{ Storage::url($item->poster) }}" class="w-full h-full object-cover transition-transform group-hover:scale-105" alt="Poster Kegiatan">
                    @else
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-purple-50 opacity-80"></div>
                        <svg class="w-12 h-12 text-indigo-200 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    @endif
                </div>

                <div class="p-6 flex-1 flex flex-col">
                    <h3 class="text-lg font-bold text-slate-900 leading-tight mb-2">{{ $item->judul }}</h3>
                    
                    <div class="flex items-start gap-2 mt-2 mb-1">
                        <svg class="w-4 h-4 text-indigo-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="text-sm font-medium text-slate-700">
                            {{ $item->tanggal_mulai ? $item->tanggal_mulai->format('d M Y, H:i') : '-' }} 
                            @if($item->tanggal_selesai)
                                <br><span class="text-slate-400 text-xs font-normal">s/d {{ $item->tanggal_selesai->format('d M Y, H:i') }}</span>
                            @endif
                        </span>
                    </div>
                    
                    @if(auth()->user()->hasRole(['admin', 'staff_dewan']))
                    <div class="flex items-start gap-2 mb-1">
                        <svg class="w-4 h-4 text-emerald-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span class="text-sm font-medium text-slate-700">{{ $item->organisasi->nama ?? 'DPM' }}</span>
                    </div>
                    @endif

                    <div class="flex items-start gap-2 mb-4">
                        <svg class="w-4 h-4 text-rose-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="text-sm font-medium text-slate-700">{{ $item->lokasi }}</span>
                    </div>

                    <p class="text-sm text-slate-500 line-clamp-2 mb-4">{{ strip_tags($item->deskripsi) }}</p>
                    
                    <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                        <button wire:click="togglePublish({{ $item->id }})" class="text-xs font-bold {{ $item->is_published ? 'text-slate-400 hover:text-slate-600' : 'text-emerald-600 hover:text-emerald-700' }} transition-colors">
                            {{ $item->is_published ? 'Tarik Publikasi' : 'Publikasikan' }}
                        </button>
                        <div class="flex items-center gap-1">
                            <button wire:click="edit({{ $item->id }})" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <button wire:click="confirmDelete({{ $item->id }})" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 flex flex-col items-center justify-center text-center bg-white rounded-3xl border border-slate-100 border-dashed">
                <div class="w-20 h-20 bg-indigo-50 text-indigo-300 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-700">Belum Ada Kegiatan</h3>
                <p class="text-slate-500 max-w-sm mt-2">Mulai rencanakan agenda pertama untuk organisasi Anda.</p>
                <button wire:click="create" class="mt-6 px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-500/30 hover:bg-indigo-700 transition-all hover:-translate-y-0.5">
                    Buat Kegiatan Baru
                </button>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $kegiatans->links(data: ['scrollTo' => false]) }}
    </div>

    <!-- Form Modal (Create/Edit) -->
    @if($isModalOpen)
        <div class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="closeModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                    <div class="bg-white px-6 pt-6 pb-6">
                        <div class="flex justify-between items-center mb-5 pb-4 border-b border-slate-100">
                            <h3 class="text-xl leading-6 font-bold text-slate-800" id="modal-title">
                                {{ $kegiatan_id ? 'Edit Kegiatan' : 'Tambah Kegiatan Baru' }}
                            </h3>
                            <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-full p-2 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        <form wire:submit.prevent="save">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Kiri: Info Dasar -->
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Judul Kegiatan <span class="text-rose-500">*</span></label>
                                        <input wire:model="judul" type="text" class="w-full border-slate-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: Seminar Nasional Teknologi" required>
                                        @error('judul') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    @if(auth()->user()->hasRole(['admin', 'staff_dewan']))
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Pilih Organisasi (BEM/HMPS/UKM) <span class="text-rose-500">*</span></label>
                                        <select wire:model="organisasi_id" class="w-full border-slate-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                            <option value="">-- Pilih Organisasi --</option>
                                            @foreach($organisasis as $org)
                                                <option value="{{ $org->id }}">{{ $org->nama }} ({{ strtoupper($org->singkatan) }})</option>
                                            @endforeach
                                        </select>
                                        @error('organisasi_id') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    @endif

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Lokasi <span class="text-rose-500">*</span></label>
                                        <input wire:model="lokasi" type="text" class="w-full border-slate-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Gedung Serbaguna Polmed" required>
                                        @error('lokasi') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Mulai <span class="text-rose-500">*</span></label>
                                            <input wire:model="tanggal_mulai" type="datetime-local" class="w-full border-slate-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                            @error('tanggal_mulai') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Selesai</label>
                                            <input wire:model="tanggal_selesai" type="datetime-local" class="w-full border-slate-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @error('tanggal_selesai') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Poster Kegiatan (Maks 2MB)</label>
                                        <input wire:model="poster" type="file" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all border border-slate-200 rounded-xl p-1">
                                        @error('poster') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                                        
                                        <div wire:loading wire:target="poster" class="text-xs text-indigo-600 mt-2 font-medium">Mengunggah gambar...</div>
                                        
                                        @if ($poster)
                                            <div class="mt-3 relative w-32 h-32 rounded-xl overflow-hidden border border-slate-200 flex items-center justify-center bg-slate-100">
                                                @if(method_exists($poster, 'isPreviewable') && $poster->isPreviewable())
                                                    <img src="{{ $poster->temporaryUrl() }}" class="object-cover w-full h-full">
                                                @else
                                                    <div class="p-2 text-center text-xs text-slate-600">
                                                        {{ method_exists($poster, 'getClientOriginalName') ? $poster->getClientOriginalName() : 'File Poster' }}
                                                    </div>
                                                @endif
                                            </div>
                                        @elseif($old_poster)
                                            <div class="mt-3 relative w-32 h-32 rounded-xl overflow-hidden border border-slate-200">
                                                <img src="{{ Storage::url($old_poster) }}" class="object-cover w-full h-full">
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Kanan: Deskripsi & Kontak -->
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi / Detail <span class="text-rose-500">*</span></label>
                                        <textarea wire:model="deskripsi" rows="7" class="w-full border-slate-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Tuliskan tujuan kegiatan, peserta, dan info penting lainnya..." required></textarea>
                                        @error('deskripsi') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Narahubung (CP)</label>
                                            <input wire:model="kontak_penanggung_jawab" type="text" class="w-full border-slate-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Nama PJ">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-1">No. Kontak (WA)</label>
                                            <input wire:model="no_kontak" type="text" class="w-full border-slate-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="08123456789">
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3 p-4 mt-2 bg-indigo-50/50 border border-indigo-100 rounded-xl">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" wire:model="is_published" class="sr-only peer">
                                            <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                        </label>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-800">Publikasikan Langsung</span>
                                            <span class="text-xs text-slate-500">Tampil di halaman Landing & Mahasiswa.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-slate-100">
                                <button type="button" wire:click="closeModal" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 rounded-xl font-medium text-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-200 transition-colors">Batal</button>
                                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-bold text-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-md shadow-indigo-500/30 transition-all flex items-center min-w-[150px] justify-center">
                                    <span wire:loading.remove wire:target="save">Simpan Kegiatan</span>
                                    <span wire:loading.flex wire:target="save" class="items-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        Menyimpan...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($isDeleteModalOpen)
        <div class="fixed inset-0 z-[110] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="$set('isDeleteModalOpen', false)"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                    <div class="bg-white px-6 pt-6 pb-6">
                        <div class="sm:flex sm:items-start mb-6">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-rose-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-bold text-slate-800" id="modal-title">Hapus Kegiatan</h3>
                                <div class="mt-2 text-sm text-slate-500">
                                    <p>Apakah Anda yakin ingin menghapus data kegiatan ini secara permanen?</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                            <button type="button" wire:click="$set('isDeleteModalOpen', false)" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 rounded-xl font-medium text-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-200 transition-colors">Batal</button>
                            <button wire:click="delete" type="button" class="px-5 py-2.5 bg-rose-600 text-white rounded-xl font-bold text-sm hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500 shadow-md shadow-rose-500/30 transition-all flex items-center min-w-[120px] justify-center">
                                <span wire:loading.remove wire:target="delete">Ya, Hapus</span>
                                <span wire:loading.flex wire:target="delete" class="items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Menghapus
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

