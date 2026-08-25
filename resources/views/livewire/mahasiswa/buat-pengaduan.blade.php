<div>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-rose-500 to-orange-500 text-white flex items-center justify-center shadow-lg shadow-rose-500/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
            </div>
            <div>
                <h2 class="font-heading font-extrabold text-xl text-slate-800 leading-tight">Buat Pengaduan & Aspirasi</h2>
                <p class="text-xs text-slate-500 font-medium">Sampaikan aspirasi atau keluhan Anda kepada DPM Polmed dengan aman & transparan.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6">

        {{-- Flash Success --}}
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-2xl flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span class="font-semibold text-sm">{{ session('success') }}</span>
            </div>
        @endif

        <form wire:submit="submit" class="space-y-6">

            {{-- LANGKAH 1: Pilih Kategori --}}
            <div class="glass rounded-3xl p-6 md:p-8 border border-white/60 shadow-xl shadow-slate-200/50">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-8 h-8 rounded-full bg-indigo-600 text-white text-sm font-bold flex items-center justify-center shrink-0">1</div>
                    <h3 class="text-lg font-bold text-slate-800">Pilih Kategori Laporan</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    @foreach($kategoriList as $kat)
                        @php
                            $namaKat = strtolower($kat->nama_kategori);
                            $isSensitif = $kat->level_sensitivitas === 'tinggi' || str_contains($namaKat, 'pelecehan') || str_contains($namaKat, 'kekerasan');
                            $isSelected = $kategori_id == $kat->id;
                            
                            if (str_contains($namaKat, 'akademik') || str_contains($namaKat, 'kuliah') || str_contains($namaKat, 'nilai')) {
                                $iconSvg = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>';
                                $iconBg = 'bg-blue-50 text-blue-600 border-blue-200';
                                $activeCard = 'border-blue-500 bg-blue-50/50 ring-2 ring-blue-400 shadow-blue-500/15';
                            } elseif (str_contains($namaKat, 'fasilitas') || str_contains($namaKat, 'sarana') || str_contains($namaKat, 'gedung')) {
                                $iconSvg = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>';
                                $iconBg = 'bg-amber-50 text-amber-600 border-amber-200';
                                $activeCard = 'border-amber-500 bg-amber-50/50 ring-2 ring-amber-400 shadow-amber-500/15';
                            } elseif (str_contains($namaKat, 'kemahasiswaan') || str_contains($namaKat, 'organisasi') || str_contains($namaKat, 'himpunan') || str_contains($namaKat, 'ukm')) {
                                $iconSvg = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>';
                                $iconBg = 'bg-purple-50 text-purple-600 border-purple-200';
                                $activeCard = 'border-purple-500 bg-purple-50/50 ring-2 ring-purple-400 shadow-purple-500/15';
                            } elseif ($isSensitif) {
                                $iconSvg = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>';
                                $iconBg = 'bg-rose-50 text-rose-600 border-rose-200';
                                $activeCard = 'border-rose-500 bg-rose-50/60 ring-2 ring-rose-400 shadow-rose-500/20';
                            } else {
                                $iconSvg = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>';
                                $iconBg = 'bg-teal-50 text-teal-600 border-teal-200';
                                $activeCard = 'border-teal-500 bg-teal-50/50 ring-2 ring-teal-400 shadow-teal-500/15';
                            }

                            $cardClass = $isSelected 
                                ? $activeCard 
                                : 'border-slate-200/80 bg-white/70 hover:border-indigo-300 hover:bg-indigo-50/20 hover:shadow-md';
                        @endphp

                        <label class="relative flex cursor-pointer rounded-2xl border p-4 sm:p-5 transition-all duration-200 group {{ $cardClass }} shadow-sm">
                            <input type="radio" wire:model.live="kategori_id" value="{{ $kat->id }}" class="sr-only">
                            
                            @if($isSelected)
                                <div class="absolute top-3 right-3 w-5 h-5 rounded-full bg-indigo-600 text-white flex items-center justify-center shadow-md shadow-indigo-500/40">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                            @endif

                            <div class="flex items-start gap-3.5 w-full">
                                <div class="w-12 h-12 rounded-2xl {{ $iconBg }} border flex items-center justify-center shrink-0 shadow-inner group-hover:scale-105 transition-transform">
                                    {!! $iconSvg !!}
                                </div>
                                <div class="flex-1 min-w-0 pr-4">
                                    <span class="block text-sm font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">
                                        {{ $kat->nama_kategori }}
                                    </span>
                                    <span class="block text-xs text-slate-500 mt-1 leading-relaxed line-clamp-2">
                                        {{ $kat->deskripsi }}
                                    </span>
                                    @if($isSensitif)
                                        <span class="inline-flex items-center gap-1 text-[10px] font-extrabold text-rose-700 bg-rose-100/90 border border-rose-300 px-2.5 py-0.5 rounded-full mt-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-ping"></span>
                                            Proteksi AES-256 Otomatis
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('kategori_id') <p class="text-xs text-rose-500 mt-2 font-medium">{{ $message }}</p> @enderror

                {{-- Input Tambahan jika Kategori Lainnya --}}
                @php
                    $selectedKategori = $kategoriList->firstWhere('id', $kategori_id);
                @endphp
                @if($selectedKategori && strtolower($selectedKategori->nama_kategori) === 'lainnya')
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Sebutkan Jenis Pengaduan / Aspirasi <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            wire:model="kategori_lainnya"
                            class="w-full rounded-xl border-slate-200 bg-white text-sm px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Contoh: Parkir Liar, Kantin Kampus, UKT, Beasiswa..."
                        >
                        @error('kategori_lainnya') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                @endif
            </div>

            {{-- LANGKAH 2: Pengaturan Privasi --}}
            <div class="glass rounded-3xl p-6 md:p-8 border border-white/60 shadow-xl shadow-slate-200/50">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-8 h-8 rounded-full bg-indigo-600 text-white text-sm font-bold flex items-center justify-center shrink-0">2</div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Pengaturan Privasi</h3>
                        <p class="text-xs text-slate-500">Pilih apakah identitas Anda ditampilkan atau disembunyikan.</p>
                    </div>
                </div>

                @if($selectedKategori && $selectedKategori->level_sensitivitas === 'tinggi')
                    {{-- Kasus Sensitif: Terkunci Anonim --}}
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-rose-50 border border-rose-200">
                        <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-rose-800">Privasi Terkunci: Mode Anonim & Terenkripsi</p>
                            <p class="text-xs text-rose-600 mt-1 leading-relaxed">
                                Kategori ini diklasifikasikan sebagai <strong>sangat sensitif</strong>. Identitas Anda otomatis dienkripsi dengan standar AES-256-CBC dan disembunyikan.
                            </p>
                        </div>
                    </div>
                @else
                    {{-- Pengaduan Biasa: Pilihan Anonim / Umum --}}
                    <div class="flex items-center justify-between p-4 rounded-2xl border transition-all duration-200 {{ $is_anonim ? 'bg-slate-900 text-white border-slate-700' : 'bg-slate-50 border-slate-200' }}">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl {{ $is_anonim ? 'bg-slate-800 text-indigo-400' : 'bg-white text-slate-600 border border-slate-200' }} flex items-center justify-center shrink-0">
                                @if($is_anonim)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-bold {{ $is_anonim ? 'text-white' : 'text-slate-800' }}">
                                    {{ $is_anonim ? 'Laporan Anonim (Identitas Tersembunyi)' : 'Laporan dengan Nama (Identitas Terlihat)' }}
                                </p>
                                <p class="text-xs mt-1 {{ $is_anonim ? 'text-slate-400' : 'text-slate-500' }}">
                                    @if($is_anonim)
                                        Nama dan NIM Anda <strong class="text-slate-300">disembunyikan</strong>. DPM tidak akan tahu siapa pengirimnya.
                                    @else
                                        Nama dan NIM Anda <strong>terlihat</strong> oleh Staff DPM yang menangani laporan ini.
                                    @endif
                                </p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer shrink-0 ml-4">
                            <input type="checkbox" wire:model.live="is_anonim" class="sr-only peer">
                            <div class="w-12 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                @endif
            </div>

            {{-- LANGKAH 3: Isi Laporan --}}
            <div class="glass rounded-3xl p-6 sm:p-8 border border-white/70 shadow-xl shadow-slate-200/50" x-data="{
                insertTag(tag) {
                    let current = @this.get('isi') || '';
                    if (current && !current.endsWith('\n')) current += '\n';
                    @this.set('isi', current + tag + ': ');
                }
            }">
                <div class="flex items-center justify-between gap-3 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-indigo-600 text-white text-xs font-extrabold flex items-center justify-center shrink-0 shadow-md shadow-indigo-600/30">3</div>
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Uraian Aspirasi / Pengaduan</h3>
                            <p class="text-xs text-slate-500">Jelaskan pokok permasalahan secara jelas, padat, dan faktual</p>
                        </div>
                    </div>
                </div>

                <!-- Format Suggestion Chips -->
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <span class="text-[11px] font-bold text-slate-400">Format Cepat:</span>
                    <button type="button" @click="insertTag('[Lokasi Kejadian]')" class="text-[11px] font-bold px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 text-slate-600 transition-colors border border-slate-200">
                        + Lokasi Kejadian
                    </button>
                    <button type="button" @click="insertTag('[Waktu / Tanggal]')" class="text-[11px] font-bold px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 text-slate-600 transition-colors border border-slate-200">
                        + Waktu / Tanggal
                    </button>
                    <button type="button" @click="insertTag('[Pihak Terkait]')" class="text-[11px] font-bold px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 text-slate-600 transition-colors border border-slate-200">
                        + Pihak Terkait
                    </button>
                    <button type="button" @click="insertTag('[Harapan Solusi]')" class="text-[11px] font-bold px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 text-slate-600 transition-colors border border-slate-200">
                        + Harapan Solusi
                    </button>
                </div>

                <div class="relative">
                    <textarea
                        wire:model="isi"
                        rows="7"
                        class="block w-full rounded-2xl border-slate-200 bg-white/90 shadow-inner focus:border-indigo-500 focus:ring-indigo-500 text-sm px-4 py-3.5 leading-relaxed placeholder-slate-400 resize-none font-medium"
                        placeholder="Ceritakan secara rinci apa yang terjadi. Sertakan: siapa, kapan, di mana, apa yang terjadi, dan bukti jika ada..."
                    ></textarea>
                </div>

                <div class="flex items-center justify-between mt-2">
                    <p class="text-xs text-slate-400">Minimal 20 karakter agar laporan dapat diverifikasi.</p>
                    @if($isi)
                        <span class="text-xs font-mono font-bold {{ strlen($isi) >= 20 ? 'text-emerald-600' : 'text-amber-500' }}">
                            {{ strlen($isi) }} Karakter
                        </span>
                    @endif
                </div>
                @error('isi') <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
            </div>

            {{-- LANGKAH 4: Lampiran Foto / Bukti --}}
            <div class="glass rounded-3xl p-6 md:p-8 border border-white/60 shadow-xl shadow-slate-200/50" 
                 x-data="{
                    isConverting: false,
                    async handleUpload(event) {
                        const input = event.target;
                        const files = Array.from(input.files);
                        if (!files.length) return;

                        let hasHeic = files.some(f => f.name.toLowerCase().endsWith('.heic') || f.name.toLowerCase().endsWith('.heif'));

                        if (hasHeic && typeof heic2any !== 'undefined') {
                            this.isConverting = true;
                            try {
                                const dt = new DataTransfer();
                                for (let file of files) {
                                    const isH = file.name.toLowerCase().endsWith('.heic') || file.name.toLowerCase().endsWith('.heif');
                                    if (isH) {
                                        const blob = await heic2any({ blob: file, toType: 'image/jpeg', quality: 0.85 });
                                        const singleBlob = Array.isArray(blob) ? blob[0] : blob;
                                        const converted = new File([singleBlob], file.name.replace(/\.(heic|heif)$/i, '.jpg'), { type: 'image/jpeg' });
                                        dt.items.add(converted);
                                    } else {
                                        dt.items.add(file);
                                    }
                                }
                                input.files = dt.files;
                            } catch (e) {
                                console.warn('HEIC convert error:', e);
                            } finally {
                                this.isConverting = false;
                            }
                        }

                        @this.uploadMultiple('fotos', input.files);
                    }
                 }">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-8 h-8 rounded-full bg-slate-400 text-white text-sm font-bold flex items-center justify-center shrink-0">4</div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Lampiran Bukti <span class="text-xs font-normal text-slate-400 ml-1">(Opsional)</span></h3>
                        <p class="text-xs text-slate-500">Mendukung Foto Kamera, iPhone (HEIC), PNG, JPG, atau PDF. Maks 3 berkas.</p>
                    </div>
                </div>

                <label for="file-upload" class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-200 border-dashed rounded-2xl cursor-pointer hover:bg-indigo-50/50 hover:border-indigo-300 transition-all duration-200 group">
                    <div class="flex flex-col items-center justify-center gap-2">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 group-hover:bg-indigo-100 flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <p class="text-sm text-slate-500 group-hover:text-indigo-600 font-medium transition-colors text-center px-4">
                            <span x-show="!isConverting" wire:loading.remove wire:target="fotos">Klik atau seret foto ke sini</span>
                            <span x-show="isConverting" x-cloak class="text-indigo-600 font-bold flex items-center gap-1.5">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Mengonversi foto iPhone...
                            </span>
                            <span x-show="!isConverting" wire:loading wire:target="fotos" class="text-indigo-600 font-bold">Mengunggah berkas...</span>
                        </p>
                    </div>
                    <input id="file-upload" type="file" class="sr-only" multiple accept="image/*,.heic,.heif,.pdf" @change="handleUpload($event)">
                </label>
                @error('fotos') <p class="text-xs text-rose-500 mt-2 font-medium">{{ $message }}</p> @enderror
                @error('fotos.*') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror

                @if($fotos)
                    <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($fotos as $idx => $foto)
                            @php
                                $isPrev = method_exists($foto, 'isPreviewable') && $foto->isPreviewable();
                                $name = method_exists($foto, 'getClientOriginalName') ? $foto->getClientOriginalName() : 'Foto ' . ($idx + 1);
                                $ext = strtoupper(pathinfo($name, PATHINFO_EXTENSION) ?: 'FOTO');
                            @endphp
                            <div class="relative rounded-2xl overflow-hidden border-2 border-indigo-200 shadow-sm aspect-square bg-slate-100 flex flex-col items-center justify-center p-2 group">
                                @if($isPrev)
                                    <img src="{{ $foto->temporaryUrl() }}" class="w-full h-full object-cover rounded-xl">
                                    <div class="absolute bottom-1.5 right-1.5 bg-black/70 backdrop-blur-sm text-white px-2 py-0.5 rounded-md text-[9px] font-bold uppercase">
                                        {{ $ext }}
                                    </div>
                                @else
                                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-2 shadow-inner">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-800 truncate max-w-full px-2 text-center">{{ $name }}</span>
                                    <span class="mt-1.5 inline-block text-[9px] font-bold px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded-md uppercase tracking-wider">
                                        {{ $ext }}
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Submit --}}
            <div class="flex justify-end gap-3 pb-6">
                <a href="{{ route('home') }}" class="px-6 py-3 rounded-2xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-500/30 transition-all hover:-translate-y-0.5 flex items-center gap-2 min-w-[160px] justify-center">
                    <span wire:loading.remove wire:target="submit" class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        Kirim Laporan
                    </span>
                    <span wire:loading.flex wire:target="submit" class="items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Mengirim...
                    </span>
                </button>
            </div>

        </form>
    </div>
</div>





