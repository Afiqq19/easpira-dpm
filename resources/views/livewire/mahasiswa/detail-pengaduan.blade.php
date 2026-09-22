<div>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('mahasiswa.pengaduan.index') }}" wire:navigate class="p-2 rounded-xl bg-white/80 hover:bg-white text-slate-500 hover:text-indigo-600 transition-all shadow-sm border border-slate-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-xs font-bold tracking-wider mr-2 uppercase">Detail Tiket</span>
                <span class="font-mono font-bold text-slate-800">{{ $pengaduan->ticket_code }}</span>
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Status Tracker Bar (Kompak & 100% Horizontal ke Samping) -->
        <div class="glass p-4 sm:p-5 rounded-2xl sm:rounded-3xl shadow-md border border-white/60">
            @php
                $stepList = [
                    ['label' => 'Diterima'],
                    ['label' => 'Diverifikasi'],
                    ['label' => 'Diproses'],
                    ['label' => 'Tindak Lanjut'],
                    ['label' => 'Selesai'],
                ];
                $statuses = ['diterima', 'diverifikasi', 'diproses', 'ditindaklanjuti', 'selesai'];
                $currentIndex = array_search($pengaduan->status, $statuses);
                if ($currentIndex === false) {
                    $currentIndex = ($pengaduan->status === 'ditolak') ? -1 : 0;
                }

                $badgeStyles = [
                    'diterima' => 'bg-blue-50 text-blue-700 border-blue-200',
                    'diverifikasi' => 'bg-amber-50 text-amber-700 border-amber-200',
                    'diproses' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                    'ditindaklanjuti' => 'bg-purple-50 text-purple-700 border-purple-200',
                    'selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'ditolak' => 'bg-rose-50 text-rose-700 border-rose-200',
                ];
                $badgeStyle = $badgeStyles[$pengaduan->status] ?? 'bg-slate-50 text-slate-700 border-slate-200';
            @endphp
            
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-600 animate-pulse"></span>
                    <h3 class="text-xs sm:text-sm font-bold text-slate-800 tracking-tight">Status Penanganan</h3>
                </div>
                <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider border {{ $badgeStyle }} shadow-sm">
                    {{ $pengaduan->status }}
                </span>
            </div>
            
            @if($pengaduan->status === 'ditolak')
                <div class="p-3.5 bg-rose-50 border border-rose-200 rounded-xl flex items-start gap-3">
                    <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <div>
                        <h4 class="text-xs font-bold text-rose-800">Pengaduan Ditolak</h4>
                        <p class="text-xs text-rose-700 leading-relaxed font-medium mt-0.5">{{ $pengaduan->alasan_penolakan ?? 'Pengaduan tidak memenuhi kriteria verifikasi atau duplikasi.' }}</p>
                    </div>
                </div>
            @else
                <!-- Horizontal Stepper Row (Always 1 Row, Compact & Connected) -->
                <div class="overflow-x-auto pb-1 -mb-1">
                    <div class="min-w-[480px] sm:min-w-0 flex items-center justify-between relative px-2">
                        @foreach($stepList as $idx => $step)
                            @php
                                $isCompleted = $currentIndex > $idx;
                                $isCurrent = $currentIndex === $idx;
                                $isPending = $currentIndex < $idx;
                            @endphp

                            <!-- Node + Label -->
                            <div class="flex flex-col items-center relative z-10 w-20 text-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs transition-all duration-300 shadow-sm mb-1.5
                                    {{ $isCompleted ? 'bg-emerald-500 text-white shadow-emerald-500/30' : '' }}
                                    {{ $isCurrent ? 'bg-indigo-600 text-white ring-4 ring-indigo-100 shadow-indigo-500/40 animate-pulse' : '' }}
                                    {{ $isPending ? 'bg-white border-2 border-slate-200 text-slate-400' : '' }}">
                                    @if($isCompleted)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    @else
                                        <span>{{ $idx + 1 }}</span>
                                    @endif
                                </div>
                                <span class="text-[11px] font-bold truncate max-w-full {{ $isCurrent ? 'text-indigo-600' : ($isCompleted ? 'text-slate-800' : 'text-slate-400') }}">
                                    {{ $step['label'] }}
                                </span>
                            </div>

                            <!-- Connecting Line between nodes -->
                            @if($idx < count($stepList) - 1)
                                <div class="flex-1 h-1 mx-1 rounded-full relative -top-3 z-0 {{ $currentIndex > $idx ? 'bg-emerald-500' : ($currentIndex === $idx ? 'bg-gradient-to-r from-indigo-500 to-slate-200' : 'bg-slate-200') }} transition-all duration-500"></div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left: Isi Pengaduan -->
            <div class="md:col-span-2 space-y-6">
                <!-- Data Pengaduan -->
                <div class="glass p-6 md:p-8 rounded-3xl shadow-lg border border-white/60">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center shadow-inner">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-slate-800">Laporan Aspirasi / Pengaduan</h2>
                            <p class="text-sm text-slate-500">{{ $pengaduan->created_at->translatedFormat('l, d F Y H:i') }}</p>
                        </div>
                    </div>
                    
                    <div class="bg-white/70 rounded-2xl p-5 border border-slate-200/80 mb-6 shadow-sm">
                        <p class="text-slate-700 leading-relaxed whitespace-pre-wrap">{{ $pengaduan->isi }}</p>
                    </div>
                    
                    <!-- Lampiran Foto / Bukti -->
                    @if($pengaduan->lampiran && is_array($pengaduan->lampiran) && count($pengaduan->lampiran) > 0)
                        <h4 class="text-sm font-bold text-slate-800 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Lampiran Bukti ({{ count($pengaduan->lampiran) }})
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($pengaduan->lampiran as $path)
                                @php
                                    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                    $isDirectImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg']);
                                    $fileUrl = asset('storage/' . $path);
                                    $fileName = basename($path);
                                @endphp
                                
                                <div class="relative group overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 transition-all hover:shadow-md">
                                    @if($isDirectImage)
                                        <a href="{{ $fileUrl }}" target="_blank" class="block aspect-square overflow-hidden bg-slate-100">
                                            <img src="{{ $fileUrl }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" alt="Lampiran">
                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 text-white">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                                <span class="text-xs font-bold">Lihat Foto</span>
                                            </div>
                                        </a>
                                    @else
                                        <div class="p-5 flex flex-col items-center justify-center text-center aspect-square">
                                            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-3 shadow-inner">
                                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <span class="text-xs font-bold text-slate-800 truncate max-w-full block mb-1 uppercase tracking-wider font-mono">{{ $ext ?: 'FILE' }}</span>
                                            <span class="text-[11px] text-slate-500 truncate max-w-full block mb-3">{{ $fileName }}</span>
                                            <a href="{{ $fileUrl }}" target="_blank" download class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-sm transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                Buka / Unduh
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Tanggapan DPM -->
                <div class="glass p-6 md:p-8 rounded-3xl shadow-lg border border-white/60">
                    <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                        Tanggapan Resmi & Tindak Lanjut
                    </h3>

                    @if($pengaduan->tanggapansPublik->count() > 0)
                        <div class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 before:to-transparent">
                            @foreach($pengaduan->tanggapansPublik as $tanggapan)
                                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                                    <!-- Icon -->
                                    <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-indigo-100 text-indigo-600 shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 shadow-sm z-10">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                    </div>
                                    <!-- Card -->
                                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] p-4 rounded-2xl border border-slate-100 bg-white shadow-sm">
                                        <div class="flex items-center justify-between mb-1">
                                            <div class="font-bold text-slate-800 text-sm">
                                                @if($pengaduan->mode_privasi === 'anonim' && ($tanggapan->tipe === 'mahasiswa' || ($tanggapan->user && $tanggapan->user->hasRole('mahasiswa')) || $tanggapan->user_id === $pengaduan->user_id))
                                                    <span class="inline-flex items-center gap-1.5 text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-md text-xs font-bold border border-indigo-100">
                                                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                        Pelapor (Anonim)
                                                    </span>
                                                @else
                                                    {{ $tanggapan->user->nama ?? 'Staff DPM' }}
                                                @endif
                                            </div>
                                            <time class="text-xs font-medium text-slate-400">{{ $tanggapan->created_at->diffForHumans() }}</time>
                                        </div>
                                        <div class="text-slate-600 text-sm">
                                            {{ $tanggapan->isi_tanggapan }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            </div>
                            <p class="text-slate-500">Belum ada tanggapan resmi. Laporan Anda sedang dalam tahap antrean atau pemeriksaan.</p>
                        </div>
                    @endif

                    <!-- Form Balas Tanggapan untuk Mahasiswa -->
                    @if($pengaduan->status !== 'selesai' && $pengaduan->status !== 'ditolak')
                        <div class="mt-8 pt-6 border-t border-slate-100">
                            @if (session()->has('success_tanggapan'))
                                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl flex items-center gap-3 text-xs font-bold shadow-sm mb-4">
                                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    {{ session('success_tanggapan') }}
                                </div>
                            @endif

                            <form wire:submit="balasTanggapan" class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Beri Balasan Tambahan / Tambah Bukti</label>
                                    <textarea wire:model="isi_tanggapan" rows="3" class="w-full rounded-2xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder-slate-400" placeholder="Ketik balasan Anda ke Staff DPM di sini..."></textarea>
                                    @error('isi_tanggapan') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-md shadow-indigo-500/20 transition-all flex items-center gap-2">
                                        <span wire:loading.remove wire:target="balasTanggapan" class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                            Kirim Balasan
                                        </span>
                                        <span wire:loading wire:target="balasTanggapan" class="flex items-center gap-2">
                                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            Mengirim...
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="mt-8 pt-6 border-t border-slate-100">
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center gap-2 text-slate-500 text-sm font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Laporan ini sudah ditutup (Status: {{ ucfirst($pengaduan->status) }}), Anda tidak dapat mengirim pesan lagi.
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right: Info Samping -->
            <div class="space-y-6">
                <div class="glass p-6 rounded-3xl shadow-lg border border-white/60">
                    <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4">Informasi Tiket</h3>
                    
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 mb-0.5">Kategori</p>
                                <p class="text-sm font-bold text-slate-800">{{ $pengaduan->kategori->nama_kategori }}</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 mb-0.5">Privasi</p>
                                <span class="px-2 py-0.5 rounded text-xs font-bold {{ $pengaduan->mode_privasi === 'anonim' ? 'bg-slate-800 text-white' : 'bg-emerald-100 text-emerald-700' }}">
                                    {{ strtoupper($pengaduan->mode_privasi) }}
                                </span>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 mb-0.5">Update Terakhir</p>
                                <p class="text-sm font-bold text-slate-800">{{ $pengaduan->updated_at->diffForHumans() }}</p>
                            </div>
                        </li>
                    </ul>
                </div>
                
                @if($pengaduan->mode_privasi === 'anonim')
                <div class="glass p-6 rounded-3xl shadow-lg border border-slate-700 bg-slate-800 text-white">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <h3 class="font-bold">Mode Anonim Aktif</h3>
                    </div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Identitas Anda pada tiket ini telah disembunyikan dan dienkripsi dari staf biasa. Simpan baik-baik <strong class="text-white border-b border-dashed">Nomor Tiket</strong> Anda untuk melacak statusnya nanti.
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>


