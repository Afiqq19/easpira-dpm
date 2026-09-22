<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Kelola Program Kerja</h2>
            <p class="text-sm text-slate-500 mt-1">Daftarkan dan pantau status program kerja organisasi Anda agar dapat dievaluasi oleh mahasiswa.</p>
        </div>
        <button wire:click="create" class="inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 text-white rounded-xl font-semibold shadow-md shadow-indigo-500/30 hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Proker Baru
        </button>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl flex items-center border border-emerald-100 animate-fade-in">
            <svg class="w-5 h-5 mr-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span class="font-medium text-sm">{{ session('message') }}</span>
        </div>
    @endif

    <!-- Filter & Search Section -->
    <div class="bg-white/60 backdrop-blur-md p-4 rounded-2xl shadow-sm border border-slate-100 flex flex-col sm:flex-row gap-4">
        <div class="flex-1 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-xl leading-5 bg-white/50 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-colors" placeholder="Cari program kerja...">
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-full">Nama Program</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Pelaksanaan</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($prokers as $proker)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900">{{ $proker->nama }}</div>
                                <div class="text-xs text-slate-500 font-semibold mt-1 flex items-center gap-2">
                                    <span class="uppercase tracking-wider">{{ $proker->kategori === 'lainnya' && $proker->kategori_lainnya ? $proker->kategori_lainnya : $proker->kategori }}</span>
                                    <!-- Organisasi name not needed since it's filtered per user -->
                                </div>
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
                                <select wire:change="changeStatus({{ $proker->id }}, $event.target.value)" class="text-xs font-semibold rounded-full border-0 focus:ring-2 focus:ring-indigo-500 py-1 pl-3 pr-8 cursor-pointer appearance-none transition-colors 
                                    {{ $proker->status === 'rencana' ? 'bg-slate-100 text-slate-700' : '' }}
                                    {{ $proker->status === 'berjalan' ? 'bg-blue-100 text-blue-700 animate-pulse' : '' }}
                                    {{ $proker->status === 'selesai' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                    {{ $proker->status === 'dibatalkan' ? 'bg-rose-100 text-rose-700' : '' }}">
                                    <option value="rencana" {{ $proker->status === 'rencana' ? 'selected' : '' }}>Rencana</option>
                                    <option value="berjalan" {{ $proker->status === 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                                    <option value="selesai" {{ $proker->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="dibatalkan" {{ $proker->status === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="edit({{ $proker->id }})" class="text-indigo-600 hover:text-indigo-900 mx-2 hover:bg-indigo-50 p-1.5 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button wire:click="confirmDelete({{ $proker->id }})" class="text-rose-600 hover:text-rose-900 mx-2 hover:bg-rose-50 p-1.5 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    </div>
                                    <h3 class="text-sm font-medium text-slate-900">Belum ada Program Kerja</h3>
                                    <p class="mt-1 text-sm text-slate-500">Mulai daftarkan program kerja organisasi Anda.</p>
                                    <button wire:click="create" class="mt-4 text-indigo-600 font-medium hover:text-indigo-800">
                                        Tambah Baru &rarr;
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($prokers->hasPages())
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $prokers->links() }}
            </div>
        @endif
    </div>

    <!-- Form Modal -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="$set('isModalOpen', false)"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-slate-100">
                <form wire:submit.prevent="save">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-8 sm:pb-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-slate-800" id="modal-title">
                                {{ $proker_id ? 'Edit Program Kerja' : 'Tambah Program Kerja' }}
                            </h3>
                            <button type="button" wire:click="$set('isModalOpen', false)" class="text-slate-400 hover:text-slate-500 bg-slate-50 hover:bg-slate-100 rounded-full p-2 transition-colors">
                                <span class="sr-only">Close</span>
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-full">
                                <label for="nama" class="block text-sm font-semibold text-slate-700 mb-1">Nama Program Kerja</label>
                                <input type="text" wire:model="nama" id="nama" class="block w-full border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Contoh: Seminar Nasional Teknologi 2026">
                                @error('nama') <span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>

                            <!-- Organisasi ID otomatis sesuai user -->

                            <div>
                                <label for="kategori" class="block text-sm font-semibold text-slate-700 mb-1">Kategori</label>
                                <select wire:model.live="kategori" id="kategori" class="block w-full border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-slate-50">
                                    <option value="akademik">Akademik</option>
                                    <option value="sosial">Sosial</option>
                                    <option value="olahraga">Olahraga</option>
                                    <option value="seni">Seni & Budaya</option>
                                    <option value="lainnya">Lainnya...</option>
                                </select>
                                @error('kategori') <span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>

                            @if($kategori === 'lainnya')
                            <div class="col-span-full animate-fade-in">
                                <label for="kategori_lainnya" class="block text-sm font-semibold text-slate-700 mb-1">Tuliskan Kategori</label>
                                <input type="text" wire:model="kategori_lainnya" id="kategori_lainnya" class="block w-full border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Contoh: Keagamaan">
                                @error('kategori_lainnya') <span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>
                            @endif

                            <div>
                                <label for="status" class="block text-sm font-semibold text-slate-700 mb-1">Status Proker</label>
                                <select wire:model="status" id="status" class="block w-full border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-slate-50">
                                    <option value="rencana">Masih Rencana</option>
                                    <option value="berjalan">Sedang Berjalan / Eksekusi</option>
                                    <option value="selesai">Telah Selesai</option>
                                    <option value="dibatalkan">Dibatalkan</option>
                                </select>
                                @error('status') <span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="tanggal_mulai" class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Mulai</label>
                                <input type="date" wire:model="tanggal_mulai" id="tanggal_mulai" class="block w-full border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('tanggal_mulai') <span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="tanggal_selesai" class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Selesai (Opsional)</label>
                                <input type="date" wire:model="tanggal_selesai" id="tanggal_selesai" class="block w-full border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('tanggal_selesai') <span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-span-full">
                                <label for="deskripsi" class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi Singkat (Opsional)</label>
                                <textarea wire:model="deskripsi" id="deskripsi" rows="3" class="block w-full border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Jelaskan secara singkat tujuan atau target program kerja ini..."></textarea>
                                @error('deskripsi') <span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-5 sm:px-8 sm:flex sm:flex-row-reverse border-t border-slate-100">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-5 py-2.5 bg-indigo-600 text-base font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Simpan Data
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

    <!-- Delete Confirmation Modal -->
    @if($isDeleteModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="$set('isDeleteModalOpen', false)"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-rose-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-bold text-slate-900" id="modal-title">Hapus Program Kerja</h3>
                            <div class="mt-2">
                                <p class="text-sm text-slate-500">Apakah Anda yakin ingin menghapus program kerja <span class="font-semibold text-slate-800">{{ $prokerToDelete->nama ?? '' }}</span>? Data evaluasi yang sudah masuk juga akan terhapus. Tindakan ini tidak dapat dibatalkan.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="delete" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-rose-600 text-base font-medium text-white hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Ya, Hapus
                    </button>
                    <button type="button" wire:click="$set('isDeleteModalOpen', false)" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
