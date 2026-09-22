<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Pantau Evaluasi Program Kerja</h2>
            <p class="text-sm text-slate-500 mt-1">Daftar masukan, kritik, dan saran dari mahasiswa terhadap program kerja BEM/HMPS/UKM.</p>
        </div>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl flex items-center border border-emerald-100 animate-fade-in">
            <svg class="w-5 h-5 mr-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span class="font-medium text-sm">{{ session('message') }}</span>
        </div>
    @endif

    <!-- Filter & Search Section -->
    <div class="bg-white/60 backdrop-blur-md p-4 rounded-2xl shadow-sm border border-slate-100 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-xl leading-5 bg-white/50 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-colors" placeholder="Cari proker atau komentar...">
        </div>
        
        <div>
            <select wire:model.live="filterOrganisasi" class="block w-full py-2 px-3 border border-slate-200 rounded-xl leading-5 bg-white/50 focus:outline-none focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-colors">
                <option value="">Semua Organisasi</option>
                <option value="dpm">DPM Polmed (Pusat)</option>
                @foreach($organisasis as $org)
                    @if($org->id)
                        <option value="{{ $org->id }}">{{ $org->singkatan ?? $org->nama }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        
        <div>
            <select wire:model.live="filterAspek" class="block w-full py-2 px-3 border border-slate-200 rounded-xl leading-5 bg-white/50 focus:outline-none focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-colors">
                <option value="">Semua Aspek</option>
                <option value="pendaftaran">Pendaftaran</option>
                <option value="pelaksanaan">Pelaksanaan</option>
                <option value="manfaat">Manfaat</option>
                <option value="koordinasi">Koordinasi</option>
                <option value="lainnya">Lainnya</option>
            </select>
        </div>
    </div>

    <!-- Data List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($evaluasis as $evaluasi)
            <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-slate-200 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col cursor-pointer" wire:click="lihatDetail({{ $evaluasi->id }})">
                <div class="p-6 flex flex-col flex-1">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                        @if($evaluasi->is_anonim)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-slate-100 text-slate-600">Anonim</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-indigo-50 text-indigo-700">{{ $evaluasi->user->name ?? 'Mahasiswa' }}</span>
                        @endif
                    </div>
                        
                        <!-- Rating -->
                        <div class="flex gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $evaluasi->rating >= $i ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @endfor
                        </div>
                    </div>
                    
                    <h3 class="text-base font-bold text-slate-800 mb-1 leading-tight line-clamp-1">{{ $evaluasi->programKerja->nama ?? 'Proker Terhapus' }}</h3>
                    <div class="text-xs font-medium text-slate-500 mb-4">{{ $evaluasi->programKerja?->organisasi?->singkatan ?? 'DPM' }} • Aspek: {{ ucfirst($evaluasi->aspek) }}</div>
                    
                    <p class="text-sm text-slate-600 line-clamp-3 mb-4 flex-1">"{{ $evaluasi->komentar }}"</p>
                    
                    <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-400">
                            {{ $evaluasi->created_at->diffForHumans() }}
                        </span>
                        <span class="text-xs font-bold text-indigo-600 hover:text-indigo-800">Lihat Detail &rarr;</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 flex flex-col items-center justify-center text-center bg-white/50 backdrop-blur rounded-3xl border border-slate-200/60 border-dashed shadow-sm">
                <div class="w-20 h-20 bg-slate-100 text-slate-300 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-700">Belum ada evaluasi</h3>
                <p class="text-slate-500 max-w-sm mt-2">Pencarian tidak menemukan hasil, atau belum ada mahasiswa yang mengisi form evaluasi.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($evaluasis->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $evaluasis->links(data: ['scrollTo' => false]) }}
        </div>
    @endif

    <!-- Detail Modal -->
    @if($isModalOpen && $selectedEvaluasi)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="$set('isModalOpen', false)"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-slate-100">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-8 sm:pb-6">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-slate-800" id="modal-title">Detail Evaluasi</h3>
                            <p class="text-sm text-slate-500 mt-1">Disampaikan pada {{ $selectedEvaluasi->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <button type="button" wire:click="$set('isModalOpen', false)" class="text-slate-400 hover:text-slate-500 bg-slate-50 hover:bg-slate-100 rounded-full p-2 transition-colors">
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 mb-6 space-y-4">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Pengirim</p>
                            @if($selectedEvaluasi->is_anonim)
                                <p class="text-slate-700 font-semibold italic">Anonim</p>
                            @else
                                <p class="text-slate-700 font-semibold">{{ $selectedEvaluasi->user->name ?? 'Mahasiswa' }}</p>
                            @endif
                        </div>
                        
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Program Kerja</p>
                            <p class="text-slate-800 font-bold">{{ $selectedEvaluasi->programKerja->nama ?? 'Proker Terhapus' }}</p>
                            <p class="text-slate-500 text-sm mt-0.5">Oleh: {{ $selectedEvaluasi->programKerja?->organisasi?->nama ?? 'DPM Polmed' }}</p>
                        </div>
                        
                        <div class="flex gap-8">
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Aspek</p>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-indigo-100 text-indigo-700">
                                    {{ ucfirst($selectedEvaluasi->aspek) }}
                                </span>
                            </div>
                            
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Rating</p>
                                <div class="flex gap-0.5 mt-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $selectedEvaluasi->rating >= $i ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Komentar / Kritik & Saran</p>
                        <div class="bg-white border border-slate-200 p-4 rounded-xl text-slate-700 leading-relaxed shadow-sm">
                            "{!! nl2br(e($selectedEvaluasi->komentar)) !!}"
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-4 sm:px-8 sm:flex sm:flex-row-reverse border-t border-slate-100 justify-between items-center">
                    <button type="button" wire:click="$set('isModalOpen', false)" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-5 py-2.5 bg-indigo-600 text-base font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Tutup
                    </button>
                    @if(auth()->user()->role === 'admin')
                    <button type="button" wire:click="delete({{ $selectedEvaluasi->id }})" wire:confirm="Yakin ingin menghapus evaluasi ini?" class="mt-3 w-full inline-flex justify-center rounded-xl border border-rose-200 shadow-sm px-5 py-2.5 bg-rose-50 text-base font-medium text-rose-700 hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors">
                        Hapus Evaluasi
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
