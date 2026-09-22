<div>
    <x-slot name="header">
        <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-sm font-medium mr-3">PENGADUAN</span>
        Manajemen Tiket Aspirasi
    </x-slot>

    @if (session()->has('message'))
        <div class="mb-4 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('success_decrypt'))
        <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-lg flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            <span class="font-bold">{{ session('success_decrypt') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="glass rounded-2xl shadow-xl shadow-indigo-500/5 p-6 mb-8">
        <div class="flex flex-col md:flex-row gap-4 justify-between items-center mb-6">
            <div class="relative w-full md:w-96">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari Kode Tiket atau Isi..." class="w-full bg-white/50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block pl-10 p-2.5">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
            
            <div class="w-full md:w-64">
                <select wire:model.live="statusFilter" class="w-full bg-white/50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 p-2.5">
                    <option value="">Semua Status</option>
                    <option value="diterima">Diterima</option>
                    <option value="diverifikasi">Diverifikasi</option>
                    <option value="diproses">Diproses</option>
                    <option value="ditindaklanjuti">Ditindaklanjuti</option>
                    <option value="selesai">Selesai</option>
                    <option value="ditolak">Ditolak</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl border border-slate-100">
            <table class="w-full text-sm text-left text-slate-500">
                <thead class="text-xs text-slate-700 uppercase bg-slate-50/50">
                    <tr>
                        <th scope="col" class="px-6 py-4 rounded-tl-xl">Tiket & Waktu</th>
                        <th scope="col" class="px-6 py-4">Kategori</th>
                        <th scope="col" class="px-6 py-4">Pelapor</th>
                        <th scope="col" class="px-6 py-4">Status</th>
                        <th scope="col" class="px-6 py-4 text-right rounded-tr-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white/40">
                    @forelse($pengaduans as $p)
                        <tr class="hover:bg-indigo-50/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-mono font-bold text-indigo-600">{{ $p->ticket_code }}</div>
                                <div class="text-xs text-slate-400 mt-1">{{ $p->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-md text-xs font-semibold {{ $p->penanganan_khusus ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $p->kategori->nama_kategori ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($p->mode_privasi === 'anonim')
                                    @if(isset($identitasTerbuka[$p->id]))
                                        <div class="bg-rose-50 border border-rose-100 p-2 rounded-lg relative group">
                                            <div class="text-xs font-bold text-rose-800">{{ $identitasTerbuka[$p->id]['nama'] }}</div>
                                            <div class="text-xs text-rose-600">{{ $identitasTerbuka[$p->id]['nim'] ?? 'NIM tidak tersedia' }}</div>
                                            <button wire:click="tutupIdentitas({{ $p->id }})" class="absolute top-1 right-1 p-1 bg-white rounded-full text-slate-400 hover:text-rose-600 shadow-sm opacity-0 group-hover:opacity-100 transition-opacity" title="Tutup Identitas">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-slate-800 text-slate-200">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                ANONIM
                                            </span>
                                            @can('penanganan_kasus_sensitif')
                                                <button wire:click="bukaIdentitas({{ $p->id }})" class="text-xs font-medium text-rose-600 hover:text-rose-800 underline underline-offset-2" onclick="return confirm('PERINGATAN: Membuka identitas anonim adalah tindakan sensitif. Tindakan ini akan dicatat dalam Log Audit secara permanen. Lanjutkan?')">
                                                    Buka
                                                </button>
                                            @endcan
                                        </div>
                                    @endif
                                @else
                                    <div class="text-sm font-medium text-slate-900">{{ $p->user->nama ?? 'Unknown' }}</div>
                                    <div class="text-xs text-slate-500">{{ $p->user->nim ?? '-' }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if(auth()->user()->isEksekutif())
                                    <span class="text-sm font-semibold {{ $p->status === 'selesai' ? 'text-emerald-600' : ($p->status === 'ditolak' ? 'text-rose-600' : 'text-indigo-600') }}">
                                        {{ $p->label_status }}
                                    </span>
                                @else
                                    <select wire:change="updateStatus({{ $p->id }}, $event.target.value)" class="bg-transparent border-none text-sm font-semibold focus:ring-0 p-0 pr-6 {{ $p->status === 'selesai' ? 'text-emerald-600' : ($p->status === 'ditolak' ? 'text-rose-600' : 'text-indigo-600') }}">
                                        <option value="diterima" {{ $p->status == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                        <option value="diverifikasi" {{ $p->status == 'diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
                                        <option value="diproses" {{ $p->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                        <option value="ditindaklanjuti" {{ $p->status == 'ditindaklanjuti' ? 'selected' : '' }}>Ditindaklanjuti</option>
                                        <option value="selesai" {{ $p->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="ditolak" {{ $p->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ Auth::user()->hasRole('admin') ? route('admin.pengaduan.detail', $p->ticket_code) : (Auth::user()->isEksekutif() ? route('eksekutif.pengaduan.detail', $p->ticket_code) : route('dewan.pengaduan.detail', $p->ticket_code)) }}" wire:navigate class="inline-block text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500 bg-white/40">
                                Tidak ada data pengaduan yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $pengaduans->links() }}
        </div>
    </div>
</div>
