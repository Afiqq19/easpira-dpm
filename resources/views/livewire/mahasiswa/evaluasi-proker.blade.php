<div class="space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">
                @if(isset($user) && $user->hasRole('staff_dewan'))
                    Beri Evaluasi Program Kerja Organisasi
                @elseif(isset($user) && $user->hasRole(['hmps', 'ukm']))
                    Beri Evaluasi BEM
                @else
                    Evaluasi Program Kerja
                @endif
            </h2>
            <p class="text-sm text-slate-500 mt-1">
                @if(isset($user) && $user->hasRole('staff_dewan'))
                    Berikan masukan, kritik, dan saran untuk seluruh program kerja BEM, HMPS, dan UKM.
                @elseif(isset($user) && $user->hasRole(['hmps', 'ukm']))
                    Berikan masukan, kritik, dan saran untuk program kerja BEM.
                @else
                    Berikan masukan, kritik, dan saran untuk program kerja BEM, HMPS, maupun UKM.
                @endif
            </p>
        </div>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl flex items-center border border-emerald-100 animate-fade-in">
            <svg class="w-5 h-5 mr-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span class="font-medium text-sm">{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('message_error'))
        <div class="bg-rose-50 text-rose-600 p-4 rounded-xl flex items-center border border-rose-100 animate-fade-in">
            <svg class="w-5 h-5 mr-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
            <span class="font-medium text-sm">{{ session('message_error') }}</span>
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white/60 backdrop-blur-md p-4 rounded-2xl shadow-sm border border-slate-100 flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-2.5 border border-slate-200 rounded-xl leading-5 bg-white/50 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-colors" placeholder="Cari nama proker atau organisasi...">
        </div>
        <div class="md:w-64">
            <select wire:model.live="filterOrganisasi" class="block w-full py-2.5 px-3 border border-slate-200 rounded-xl leading-5 bg-white/50 focus:outline-none focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-colors">
                <option value="">Semua Organisasi</option>
                <option value="dpm">DPM Polmed (Pusat)</option>
                @foreach($organisasis as $org)
                    @if($org->id)
                        <option value="{{ $org->id }}">{{ $org->singkatan ?? $org->nama }}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($prokers as $proker)
            @php
                $org = $proker->organisasi;
                $warna = $org ? $org->warna : ['bg'=>'bg-violet-700','light'=>'bg-violet-100','text'=>'text-violet-700','badge'=>'bg-violet-700 text-white','border'=>'border-violet-300','hex'=>'#6d28d9'];
            @endphp
            <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-slate-200 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col group relative {{ in_array($proker->id, $evaluatedProkerIds) ? 'ring-2 ring-emerald-200' : '' }}">
                <!-- Color Strip Top -->
                <div class="absolute top-0 left-0 right-0 h-1.5 {{ in_array($proker->id, $evaluatedProkerIds) ? 'bg-emerald-500' : $warna['bg'] }}"></div>
                
                <div class="p-6 flex flex-col flex-1 mt-1">
                    <!-- Org Badge -->
                    <div class="mb-4 flex justify-between items-start">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black {{ $warna['badge'] }} shadow-sm">
                            @if($org)
                                {{ $org->tipe }} • {{ $org->singkatan ?? $org->nama }}
                            @else
                                DPM POLMED
                            @endif
                        </span>
                        
                        @if(in_array($proker->id, $evaluatedProkerIds))
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                Sudah Dievaluasi
                            </span>
                        @elseif($proker->status === 'berjalan')
                            <span class="flex h-3 w-3 relative" title="Sedang Berjalan">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                            </span>
                        @endif
                    </div>
                    
                    <h3 class="text-xl font-bold text-slate-800 mb-2 leading-tight group-hover:{{ $warna['text'] }} transition-colors">{{ $proker->nama }}</h3>
                    
                    <p class="text-sm text-slate-500 line-clamp-2 mb-4 flex-1">{{ $proker->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                    
                    <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                        <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">
                            {{ ucfirst($proker->kategori) }}
                        </div>
                        @if(in_array($proker->id, $evaluatedProkerIds))
                            <span class="inline-flex items-center text-sm font-bold text-slate-400 cursor-not-allowed">
                                Sudah Dinilai ✓
                            </span>
                        @else
                            <button wire:click="bukaModalEvaluasi({{ $proker->id }})" class="inline-flex items-center text-sm font-bold {{ $warna['text'] }} hover:opacity-70 transition-opacity">
                                Beri Evaluasi
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 flex flex-col items-center justify-center text-center bg-white/50 backdrop-blur rounded-3xl border-2 border-slate-200/60 border-dashed shadow-sm">
                <div class="w-20 h-20 bg-slate-100 text-slate-300 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-700">Tidak ada program kerja aktif</h3>
                <p class="text-slate-500 max-w-sm mt-2">Belum ada program kerja yang dibuka untuk evaluasi publik.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($prokers->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $prokers->links(data: ['scrollTo' => false]) }}
        </div>
    @endif

    <!-- Form Modal Evaluasi -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="$set('isModalOpen', false)"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-slate-100">
                <form wire:submit.prevent="simpanEvaluasi">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-8 sm:pb-6">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <h3 class="text-xl font-bold text-slate-800" id="modal-title">Formulir Evaluasi</h3>
                                <p class="text-sm text-slate-500 mt-1">Proker: <span class="font-semibold text-slate-700">{{ $selectedProker?->nama }}</span></p>
                            </div>
                            <button type="button" wire:click="$set('isModalOpen', false)" class="text-slate-400 hover:text-slate-500 bg-slate-50 hover:bg-slate-100 rounded-full p-2 transition-colors">
                                <span class="sr-only">Close</span>
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>

                        <div class="space-y-6">
                            <!-- Rating -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Penilaian Anda (1-5)</label>
                                <div class="flex items-center gap-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <button type="button" wire:click="setRating({{ $i }})" class="focus:outline-none focus:scale-110 transition-transform">
                                            <svg class="w-10 h-10 {{ $rating >= $i ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        </button>
                                    @endfor
                                </div>
                                @error('rating') <span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>

                            <!-- Aspek -->
                            <div>
                                <label for="aspek" class="block text-sm font-semibold text-slate-700 mb-1">Aspek yang Disorot</label>
                                <select wire:model.live="aspek" id="aspek" class="block w-full border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-slate-50">
                                    <option value="pendaftaran">Proses Pendaftaran</option>
                                    <option value="pelaksanaan">Pelaksanaan Kegiatan</option>
                                    <option value="manfaat">Manfaat untuk Mahasiswa</option>
                                    <option value="koordinasi">Koordinasi & Informasi</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                                @error('aspek') <span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>

                            <!-- Aspek Lainnya Input -->
                            @if($aspek === 'lainnya')
                            <div class="animate-fade-in">
                                <label for="aspek_lainnya" class="block text-sm font-semibold text-slate-700 mb-1">Sebutkan Aspek Lainnya</label>
                                <input type="text" wire:model="aspek_lainnya" id="aspek_lainnya" class="block w-full border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Contoh: Kualitas Konsumsi, Dokumentasi, dll">
                                @error('aspek_lainnya') <span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>
                            @endif

                            <!-- Komentar -->
                            <div>
                                <label for="komentar" class="block text-sm font-semibold text-slate-700 mb-1">Komentar / Kritik & Saran</label>
                                <textarea wire:model="komentar" id="komentar" rows="4" class="block w-full border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Berikan kritik dan saran Anda mengenai program kerja ini..."></textarea>
                                @error('komentar') <span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                                <p class="text-xs text-slate-500 mt-1">Gunakan bahasa yang sopan dan membangun. Minimal 10 karakter.</p>
                            </div>
                            
                            <!-- Anonim -->
                            @if(isset($user) && $user->hasRole(['hmps', 'ukm', 'staff_dewan']))
                            <div class="flex items-start bg-slate-50 p-4 rounded-xl border border-slate-200">
                                <div class="flex items-center h-5">
                                    <input id="is_anonim" wire:model="is_anonim" type="checkbox" class="focus:ring-indigo-500 h-5 w-5 text-indigo-600 border-slate-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_anonim" class="font-medium text-slate-800">Sembunyikan Nama Saya (Anonim) dari Organisasi Lain</label>
                                    <p class="text-slate-500 mt-0.5">Jika dicentang, nama Anda akan tampil sebagai <strong>"Anonim"</strong> untuk sesama HMPS/UKM. Namun, <strong class="text-indigo-600">Admin & Staff DPM tetap dapat melihat nama asli Anda</strong>.</p>
                                </div>
                            </div>
                            @else
                            <div class="flex items-start bg-slate-50 p-4 rounded-xl border border-slate-200">
                                <div class="flex items-center h-5">
                                    <input id="is_anonim" wire:model="is_anonim" type="checkbox" class="focus:ring-indigo-500 h-5 w-5 text-indigo-600 border-slate-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_anonim" class="font-medium text-slate-800">Sembunyikan Nama Saya (Anonim)</label>
                                    <p class="text-slate-500">Nama Anda tidak akan ditampilkan kepada publik maupun panitia penyelenggara.</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-5 sm:px-8 sm:flex sm:flex-row-reverse border-t border-slate-100">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-5 py-2.5 bg-indigo-600 text-base font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Kirim Evaluasi
                        </button>
                        <button type="button" wire:click="$set('isModalOpen', false)" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-5 py-2.5 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
