<div class="space-y-5">
    <!-- Header Section -->
    <div>
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Dashboard Pusat DPM</h2>
        <p class="text-sm text-slate-500 mt-1">Ringkasan statistik operasional organisasi dan pengaduan mahasiswa.</p>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Stat: Total Pengaduan -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200 relative overflow-hidden">
            <div class="absolute -right-3 -bottom-3 w-20 h-20 bg-rose-50 rounded-full opacity-50 pointer-events-none"></div>
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-rose-100 text-rose-600 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Pengaduan</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ $totalPengaduan }}</h3>
                </div>
            </div>
            <p class="text-xs text-slate-600 font-medium"><span class="text-rose-600 font-bold">{{ $pengaduanBaru }}</span> belum diproses</p>
        </div>

        <!-- Stat: Pengaduan Selesai -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200 relative overflow-hidden">
            <div class="absolute -right-3 -bottom-3 w-20 h-20 bg-emerald-50 rounded-full opacity-50 pointer-events-none"></div>
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Diselesaikan</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ $pengaduanSelesai }}</h3>
                </div>
            </div>
            <p class="text-xs text-slate-600 font-medium">Dari total pengaduan</p>
        </div>

        <!-- Stat: Total Proker -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200 relative overflow-hidden">
            <div class="absolute -right-3 -bottom-3 w-20 h-20 bg-indigo-50 rounded-full opacity-50 pointer-events-none"></div>
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Program Kerja</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ $totalProker }}</h3>
                </div>
            </div>
            <p class="text-xs text-slate-600 font-medium"><span class="text-indigo-600 font-bold">{{ $prokerBerjalan }}</span> sedang berjalan</p>
        </div>

        <!-- Stat: Organisasi -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200 relative overflow-hidden">
            <div class="absolute -right-3 -bottom-3 w-20 h-20 bg-amber-50 rounded-full opacity-50 pointer-events-none"></div>
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Organisasi Aktif</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ $totalOrganisasi }}</h3>
                </div>
            </div>
            <p class="text-xs text-slate-600 font-medium">HMPS & UKM terdaftar</p>
        </div>
    </div>

    <!-- Charts & Proker Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- Main Line Chart (2 Cols) -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 lg:col-span-2 min-w-0 overflow-hidden flex flex-col h-fit">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <div>
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-5 bg-gradient-to-b from-indigo-500 to-indigo-600 rounded-full"></div>
                        <h3 class="text-base font-bold text-slate-800 tracking-tight">Tren Pengaduan Mahasiswa</h3>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5 pl-4">6 bulan terakhir</p>
                </div>
                
                <div class="flex items-center gap-2 self-start sm:self-auto pl-4 sm:pl-0">
                    <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50/80 border border-indigo-100 text-xs font-medium text-slate-700">
                        <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                        <span>Masuk: <strong class="text-indigo-600 font-bold">{{ $totalPengaduan }}</strong></span>
                    </div>
                    <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50/80 border border-emerald-100 text-xs font-medium text-slate-700">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Selesai: <strong class="text-emerald-600 font-bold">{{ $pengaduanSelesai }}</strong></span>
                    </div>
                </div>
            </div>

            <div class="w-full min-w-0 relative">
                <div id="chart-pengaduan-dewan" wire:ignore class="w-full"></div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-5 lg:col-span-1 min-w-0">
            <!-- Proker Mendatang -->
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 min-w-0">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-4 bg-indigo-500 rounded-full"></div>
                        <h3 class="text-sm font-bold text-slate-800">Proker Mendatang</h3>
                    </div>
                    <a href="{{ route('dewan.proker-kegiatan.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">Lihat Semua &rarr;</a>
                </div>
                <div class="space-y-2">
                    @forelse($upcomingProkers as $proker)
                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 hover:bg-indigo-50/50 transition-colors">
                        <div class="flex-1 min-w-0 mr-2">
                            <p class="font-bold text-slate-800 text-xs truncate">{{ $proker->nama }}</p>
                            <div class="flex items-center gap-1.5 mt-0.5 text-[11px] text-slate-500">
                                <span class="font-semibold text-indigo-600 truncate">{{ $proker->organisasi->singkatan ?? $proker->organisasi->nama ?? '-' }}</span>
                                <span>&bull;</span>
                                <span class="shrink-0">{{ $proker->tanggal_mulai ? $proker->tanggal_mulai->translatedFormat('d M Y') : '-' }}</span>
                            </div>
                        </div>
                        <span class="inline-flex flex-shrink-0 items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                            {{ $proker->status === 'rencana' ? 'bg-slate-200 text-slate-700' : '' }}
                            {{ $proker->status === 'berjalan' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $proker->status === 'selesai' ? 'bg-emerald-100 text-emerald-700' : '' }}
                            {{ $proker->status === 'dibatalkan' ? 'bg-rose-100 text-rose-700' : '' }}">
                            {{ $proker->status }}
                        </span>
                    </div>
                    @empty
                    <p class="text-xs text-slate-500 text-center py-4">Belum ada program kerja.</p>
                    @endforelse
                </div>
            </div>

            <!-- Organisasi Teraktif -->
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200/80 min-w-0">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-2 h-4 bg-amber-500 rounded-full"></div>
                    <h3 class="text-sm font-bold text-slate-800">Organisasi Teraktif</h3>
                </div>
                <ul class="space-y-2">
                    @forelse($leaderboard as $idx => $org)
                    <li class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50">
                        <div class="flex items-center gap-2.5 min-w-0 mr-2">
                            <div class="w-7 h-7 rounded-full font-bold text-xs flex items-center justify-center shrink-0
                                {{ $idx === 0 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $idx + 1 }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-slate-800 truncate">{{ $org->singkatan ?? $org->nama }}</p>
                                <p class="text-[11px] text-slate-500 truncate">{{ $org->tipe }}</p>
                            </div>
                        </div>
                        <span class="inline-flex shrink-0 items-center justify-center px-2 py-0.5 text-[11px] font-bold bg-indigo-50 text-indigo-700 rounded-lg">
                            {{ $org->program_kerja_count }} Proker
                        </span>
                    </li>
                    @empty
                    <li class="text-xs text-slate-500 text-center py-4">Belum ada data organisasi.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:navigated', function() {
            var el = document.querySelector("#chart-pengaduan-dewan");
            if (!el || el.dataset.rendered) return;

            var bulanList = @json($bulanList);
            var masukChart = @json($pengaduanMasukChart);
            var selesaiChart = @json($pengaduanSelesaiChart);

            var bulanShort = bulanList.map(function(item) {
                return item ? item.split(' ')[0] : item;
            });

            var optionsPengaduan = {
                series: [{
                    name: 'Pengaduan Masuk',
                    data: masukChart
                }, {
                    name: 'Pengaduan Selesai',
                    data: selesaiChart
                }],
                chart: {
                    type: 'area',
                    height: 220,
                    width: '100%',
                    fontFamily: 'Inter, sans-serif',
                    toolbar: { show: false },
                    zoom: { enabled: false },
                    parentHeightOffset: 0,
                },
                colors: ['#6366f1', '#10b981'],
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: [3, 3], lineCap: 'round' },
                markers: {
                    size: 4,
                    colors: ['#ffffff', '#ffffff'],
                    strokeColors: ['#6366f1', '#10b981'],
                    strokeWidth: 2.5,
                    hover: { size: 7 }
                },
                fill: {
                    type: 'gradient',
                    gradient: { type: 'vertical', shadeIntensity: 1, opacityFrom: 0.25, opacityTo: 0.02, stops: [0, 95, 100] }
                },
                grid: {
                    borderColor: '#f1f5f9', strokeDashArray: 4,
                    xaxis: { lines: { show: false } }, yaxis: { lines: { show: true } },
                    padding: { top: 5, right: 15, bottom: 0, left: 10 }
                },
                xaxis: {
                    type: 'category', categories: bulanShort, tickPlacement: 'on',
                    axisBorder: { show: false }, axisTicks: { show: false },
                    labels: { rotate: 0, trim: false, style: { colors: '#94a3b8', fontSize: '11px', fontFamily: 'Inter, sans-serif', fontWeight: 600 } },
                    tooltip: { enabled: false }
                },
                yaxis: {
                    labels: { formatter: function(val) { return Math.floor(val) }, style: { colors: '#94a3b8', fontSize: '11px', fontFamily: 'Inter, sans-serif', fontWeight: 500 } },
                    min: 0, forceNiceScale: true
                },
                legend: { show: false },
                tooltip: {
                    theme: 'light', shared: true, intersect: false,
                    x: { formatter: function(val, opts) { var idx = opts ? opts.dataPointIndex : -1; return (idx >= 0 && bulanList[idx]) ? bulanList[idx] : val; } },
                    y: { formatter: function(val) { return val + ' Pengaduan'; } }
                }
            };

            function renderChart() {
                if (typeof window.ApexCharts === 'undefined') return;
                requestAnimationFrame(function() {
                    el.innerHTML = '';
                    var w = Math.floor(el.clientWidth || el.getBoundingClientRect().width);
                    if (w > 0) optionsPengaduan.chart.width = w;
                    if (window.__chartPengaduanDewan) { try { window.__chartPengaduanDewan.destroy(); } catch(e) {} }
                    window.__chartPengaduanDewan = new window.ApexCharts(el, optionsPengaduan);
                    window.__chartPengaduanDewan.render();
                    el.dataset.rendered = 'true';
                });
            }

            if (typeof window.ApexCharts !== 'undefined') { renderChart(); }
            else {
                var s = document.createElement('script');
                s.src = 'https://cdn.jsdelivr.net/npm/apexcharts';
                s.onload = renderChart;
                document.head.appendChild(s);
            }

            if (window.ResizeObserver && el.parentElement) {
                var ro = new ResizeObserver(function(entries) {
                    for (var i = 0; i < entries.length; i++) {
                        var newW = Math.floor(entries[i].contentRect.width);
                        if (newW > 0 && window.__chartPengaduanDewan && Math.abs(newW - (optionsPengaduan.chart.width || 0)) > 5) {
                            optionsPengaduan.chart.width = newW;
                            window.__chartPengaduanDewan.updateOptions({ chart: { width: newW } });
                        }
                    }
                });
                ro.observe(el.parentElement);
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            var event = new Event('livewire:navigated');
            document.dispatchEvent(event);
        });
    </script>
</div>
