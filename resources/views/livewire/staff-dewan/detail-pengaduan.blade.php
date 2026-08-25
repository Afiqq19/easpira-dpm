<div>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ Auth::user()->hasRole('admin') ? route('admin.pengaduan.index') : route('dewan.pengaduan.index') }}" wire:navigate class="p-2 rounded-xl bg-white/80 hover:bg-white text-slate-500 hover:text-indigo-600 transition-all shadow-sm border border-slate-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-xs font-bold tracking-wider mr-2 uppercase">Manajemen Tiket</span>
                <span class="font-mono font-bold text-slate-800">{{ $pengaduan->ticket_code }}</span>
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto space-y-6">
        @if (session()->has('success_status'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="text-sm font-bold">{{ session('success_status') }}</span>
            </div>
        @endif

        <!-- Status Management Card (Kompak & 100% Horizontal ke Samping) -->
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
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-600 animate-pulse"></span>
                    <h3 class="text-xs sm:text-sm font-bold text-slate-800 tracking-tight">Status Penanganan</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $badgeStyle }} ml-1">
                        {{ $pengaduan->status }}
                    </span>
                </div>
                
                <!-- Quick Status Selector Dropdown -->
                <div class="flex items-center gap-2">
                    <label class="text-xs font-bold text-slate-500 whitespace-nowrap hidden sm:inline">Ubah Status:</label>
                    <select wire:model.live="status_baru" class="rounded-xl border-slate-300 text-xs font-bold text-slate-700 bg-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-1.5 pl-3 pr-8">
                        <option value="diterima">Diterima</option>
                        <option value="diverifikasi">Diverifikasi</option>
                        <option value="diproses">Sedang Diproses</option>
                        <option value="ditindaklanjuti">Ditindaklanjuti</option>
                        <option value="selesai">Selesai</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                    <div wire:loading wire:target="status_baru" class="animate-spin h-4 w-4 text-indigo-600">
                        <svg fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                </div>
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
                            <h2 class="text-xl font-bold text-slate-800">Isi Pengaduan</h2>
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
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" x-data="{
                            init() {
                                this.$nextTick(() => {
                                    document.querySelectorAll('[data-heic-src]').forEach(async (el) => {
                                        const url = el.getAttribute('data-heic-src');
                                        try {
                                            const res = await fetch(url);
                                            if (!res.ok) return;
                                            const blob = await res.blob();
                                            if (typeof heic2any !== 'undefined') {
                                                const converted = await heic2any({ blob: blob, toType: 'image/jpeg', quality: 0.85 });
                                                const singleBlob = Array.isArray(converted) ? converted[0] : converted;
                                                const blobUrl = URL.createObjectURL(singleBlob);
                                                el.src = blobUrl;
                                                el.classList.remove('hidden');
                                                const parent = el.closest('.heic-container');
                                                if (parent) {
                                                    const fb = parent.querySelector('.heic-fallback');
                                                    if (fb) fb.classList.add('hidden');
                                                }
                                            }
                                        } catch (e) {
                                            console.warn('HEIC inline render failed:', e);
                                        }
                                    });
                                });
                            }
                        }">
                            @foreach($pengaduan->lampiran as $path)
                                @php
                                    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                    $isDirectImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg']);
                                    $isHeic = in_array($ext, ['heic', 'heif']);
                                    $fileUrl = asset('storage/' . $path);
                                    $fileName = basename($path);
                                @endphp
                                
                                <div class="relative group overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 transition-all hover:shadow-md aspect-square">
                                    @if($isDirectImage)
                                        <a href="{{ $fileUrl }}" target="_blank" class="block w-full h-full overflow-hidden bg-slate-100">
                                            <img src="{{ $fileUrl }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" alt="Lampiran">
                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 text-white">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                                <span class="text-xs font-bold">Lihat Foto</span>
                                            </div>
                                        </a>
                                    @elseif($isHeic)
                                        <div class="heic-container w-full h-full relative">
                                            <a href="{{ $fileUrl }}" target="_blank" class="block w-full h-full">
                                                <img data-heic-src="{{ $fileUrl }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105 hidden" alt="Foto HEIC">
                                            </a>
                                            <div class="heic-fallback p-5 flex flex-col items-center justify-center text-center w-full h-full">
                                                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-2 shadow-inner">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                                <span class="text-[10px] font-bold px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded-md uppercase mb-2">Foto iPhone (HEIC)</span>
                                                <a href="{{ $fileUrl }}" target="_blank" download class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-sm transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                    Buka Foto
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="p-5 flex flex-col items-center justify-center text-center w-full h-full">
                                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-2 shadow-inner">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <span class="text-xs font-bold text-slate-800 truncate max-w-full block mb-1 uppercase font-mono">{{ $ext ?: 'FILE' }}</span>
                                            <span class="text-[11px] text-slate-500 truncate max-w-full block mb-2">{{ $fileName }}</span>
                                            <a href="{{ $fileUrl }}" target="_blank" download class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-sm transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                Unduh Berkas
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Tanggapan -->
                <div class="glass p-6 md:p-8 rounded-3xl shadow-lg border border-white/60">
                    <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                        Ruang Diskusi & Tanggapan
                    </h3>

                    <div class="space-y-4 mb-8">
                        @forelse($pengaduan->tanggapans as $tanggapan)
                            <div class="flex gap-3 {{ $tanggapan->user_id === Auth::id() ? 'flex-row-reverse' : '' }}">
                                <div class="w-8 h-8 rounded-full flex-shrink-0 {{ $tanggapan->user_id === Auth::id() ? 'bg-indigo-600 text-white' : 'bg-slate-200 text-slate-600' }} flex items-center justify-center font-bold text-xs">
                                    {{ substr($tanggapan->user->nama ?? 'A', 0, 1) }}
                                </div>
                                <div class="max-w-[80%] rounded-2xl p-4 {{ $tanggapan->user_id === Auth::id() ? 'bg-indigo-600 text-white rounded-tr-none' : 'bg-slate-100 text-slate-800 rounded-tl-none' }}">
                                    <div class="flex items-center justify-between gap-4 mb-1">
                                        <span class="text-xs font-bold {{ $tanggapan->user_id === Auth::id() ? 'text-indigo-100' : 'text-slate-600' }}">{{ $tanggapan->user->nama ?? 'Staff' }}</span>
                                        <span class="text-[10px] {{ $tanggapan->user_id === Auth::id() ? 'text-indigo-200' : 'text-slate-400' }}">{{ $tanggapan->created_at->translatedFormat('H:i') }}</span>
                                    </div>
                                    <p class="text-sm">{{ $tanggapan->isi_tanggapan }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6 text-slate-400 text-sm">
                                Belum ada tanggapan untuk tiket ini. Kirim tanggapan pertama di bawah.
                            </div>
                        @endforelse
                    </div>

                    <form wire:submit="balasTanggapan" class="space-y-4">
                        @if (session()->has('success_tanggapan'))
                            <div class="bg-emerald-50 text-emerald-700 p-3 rounded-xl text-sm font-medium">
                                {{ session('success_tanggapan') }}
                            </div>
                        @endif
                        <div>
                            <textarea wire:model="isi_tanggapan" rows="3" class="w-full rounded-2xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder-slate-400" placeholder="Tuliskan tanggapan resmi dari dewan..."></textarea>
                            @error('isi_tanggapan') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-md shadow-indigo-500/20 transition-all">
                                Kirim Tanggapan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right: Informan & Tiket Info -->
            <div class="space-y-6">
                <!-- Informan Pelapor -->
                <div class="glass p-6 rounded-3xl shadow-lg border border-white/60">
                    <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4">Informasi Pelapor</h3>
                    
                    @if($pengaduan->mode_privasi === 'umum')
                        <div class="flex flex-col items-center text-center p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="w-16 h-16 bg-gradient-to-tr from-indigo-500 to-purple-500 rounded-full text-white flex items-center justify-center text-2xl font-bold shadow-md mb-3">
                                {{ substr($pengaduan->user->nama ?? 'M', 0, 1) }}
                            </div>
                            <h4 class="font-bold text-slate-800 text-lg">{{ $pengaduan->user->nama ?? 'Mahasiswa' }}</h4>
                            <p class="text-sm text-slate-500 font-mono mt-1">{{ $pengaduan->user->nim ?? '-' }}</p>
                            @if($pengaduan->user && $pengaduan->user->prodi)
                                <p class="text-xs font-bold text-indigo-600 mt-2 bg-indigo-50 px-3 py-1 rounded-full">{{ $pengaduan->user->prodi }}</p>
                            @endif
                        </div>
                    @else
                        <!-- Mode Anonim -->
                        @if($identitasPelapor)
                            <div class="flex flex-col items-center text-center p-4 bg-rose-50 rounded-2xl border border-rose-100 mb-4 relative overflow-hidden">
                                <div class="absolute top-0 right-0 bg-rose-500 text-white text-[10px] font-bold px-2 py-1 rounded-bl-lg">TERDEKRIPSI</div>
                                <div class="w-16 h-16 bg-gradient-to-tr from-rose-500 to-pink-500 rounded-full text-white flex items-center justify-center text-2xl font-bold shadow-md mb-3 mt-2">
                                    {{ substr($identitasPelapor['nama'] ?? 'A', 0, 1) }}
                                </div>
                                <h4 class="font-bold text-slate-800 text-lg">{{ $identitasPelapor['nama'] ?? 'Anonim' }}</h4>
                                <p class="text-sm text-slate-500 font-mono mt-1">{{ $identitasPelapor['nim'] ?? '-' }}</p>
                                <p class="text-xs font-bold text-rose-600 mt-2 bg-rose-100 px-3 py-1 rounded-full">{{ $identitasPelapor['email'] ?? '-' }}</p>
                            </div>
                            
                            @if(Auth::user()->hasRole('admin'))
                                <button wire:click="suspendPelapor" 
                                        wire:confirm="PERINGATAN: Apakah Anda yakin ingin memblokir akun ini? Pengguna ini tidak akan bisa login lagi ke sistem." 
                                        class="w-full py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold text-sm shadow-lg shadow-red-500/30 flex items-center justify-center gap-2 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    Blokir & Suspend Pelapor
                                </button>
                                @if (session()->has('success_suspend'))
                                    <div class="mt-2 text-red-600 text-xs font-bold text-center">{{ session('success_suspend') }}</div>
                                @endif
                            @endif
                        @else
                            <div class="flex flex-col items-center text-center p-6 bg-slate-800 text-white rounded-2xl border border-slate-700 mb-4 shadow-inner">
                                <svg class="w-12 h-12 text-slate-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                <h4 class="font-bold text-lg">Anonim Terenkripsi</h4>
                                <p class="text-xs text-slate-400 mt-2">Identitas pelapor dilindungi oleh sistem.</p>
                            </div>
                            
                            @if(Auth::user()->hasRole('admin'))
                                <button wire:click="bukaIdentitasDarurat" 
                                        wire:confirm="PERHATIAN: Anda akan membuka identitas anonim pelapor. Tindakan ini akan dicatat dalam Log Aktivitas dan hanya boleh dilakukan untuk keperluan darurat/investigasi. Lanjutkan?" 
                                        class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-bold text-sm shadow-lg shadow-amber-500/30 flex items-center justify-center gap-2 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                                    Buka Kunci Identitas (Darurat)
                                </button>
                                @if (session()->has('success_identitas'))
                                    <div class="mt-2 text-emerald-600 text-xs font-bold text-center">{{ session('success_identitas') }}</div>
                                @endif
                            @endif
                        @endif
                    @endif
                </div>

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
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
