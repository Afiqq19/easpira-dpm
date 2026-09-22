<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Dashboard Pusat DPM</h2>
            <p class="text-sm text-slate-500 mt-1">Ringkasan statistik operasional organisasi dan pengaduan mahasiswa.</p>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Stat: Total Pengaduan -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 flex flex-col relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-rose-50 rounded-full opacity-50 pointer-events-none"></div>
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-2xl flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total Pengaduan</p>
                    <h3 class="text-3xl font-black text-slate-800">{{ $totalPengaduan }}</h3>
                </div>
            </div>
            <p class="text-sm text-slate-600 font-medium"><span class="text-rose-600 font-bold">{{ $pengaduanBaru }}</span> belum diproses</p>
        </div>

        <!-- Stat: Pengaduan Selesai -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 flex flex-col relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-50 rounded-full opacity-50 pointer-events-none"></div>
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Telah Diselesaikan</p>
                    <h3 class="text-3xl font-black text-slate-800">{{ $pengaduanSelesai }}</h3>
                </div>
            </div>
            <p class="text-sm text-slate-600 font-medium">Dari total pengaduan</p>
        </div>

        <!-- Stat: Total Proker -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 flex flex-col relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-indigo-50 rounded-full opacity-50 pointer-events-none"></div>
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Program Kerja</p>
                    <h3 class="text-3xl font-black text-slate-800">{{ $totalProker }}</h3>
                </div>
            </div>
            <p class="text-sm text-slate-600 font-medium"><span class="text-indigo-600 font-bold">{{ $prokerBerjalan }}</span> sedang berjalan</p>
        </div>

        <!-- Stat: Organisasi -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 flex flex-col relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-amber-50 rounded-full opacity-50 pointer-events-none"></div>
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Organisasi Aktif</p>
                    <h3 class="text-3xl font-black text-slate-800">{{ $totalOrganisasi }}</h3>
                </div>
            </div>
            <p class="text-sm text-slate-600 font-medium">HMPS & UKM terdaftar</p>
        </div>
    </div>

    <!-- Charts & Proker Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Line Chart (2 Cols) -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 lg:col-span-2 min-w-0 overflow-hidden flex flex-col self-start">
            <!-- Header with Title & Custom Modern Legend Pills -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                <div>
                    <div class="flex items-center gap-2.5">
                        <div class="w-2.5 h-6 bg-gradient-to-b from-indigo-500 to-indigo-600 rounded-full"></div>
                        <h3 class="text-lg font-bold text-slate-800 tracking-tight">Tren Pengaduan Mahasiswa</h3>
                    </div>
                    <p class="text-xs text-slate-500 mt-1 pl-5">Perbandingan laporan masuk vs diselesaikan 6 bulan terakhir</p>
                </div>
                
                <!-- Modern Custom Legend Pills (Never overflows or clips) -->
                <div class="flex items-center gap-2 self-start sm:self-auto pl-5 sm:pl-0">
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-indigo-50/80 border border-indigo-100 text-xs font-medium text-slate-700 shadow-2xs">
                        <span class="w-2.5 h-2.5 rounded-full bg-indigo-600 ring-4 ring-indigo-100"></span>
                        <span>Masuk: <strong class="text-indigo-600 font-bold ml-0.5">{{ $totalPengaduan }}</strong></span>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-emerald-50/80 border border-emerald-100 text-xs font-medium text-slate-700 shadow-2xs">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 ring-4 ring-emerald-100"></span>
                        <span>Selesai: <strong class="text-emerald-600 font-bold ml-0.5">{{ $pengaduanSelesai }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- Chart Container -->
            <div class="w-full min-w-0 relative">
                <div id="chart-pengaduan" wire:ignore class="w-full"></div>
            </div>
        </div>

        <!-- Right Column: Proker Mendatang & Organisasi Teraktif (1 Col) -->
        <div class="space-y-6 lg:col-span-1 min-w-0">
            <!-- Proker Mendatang -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 min-w-0">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-4 bg-indigo-500 rounded-full"></div>
                        <h3 class="text-base font-bold text-slate-800">Proker Mendatang</h3>
                    </div>
                    <a href="{{ route('eksekutif.proker.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">Lihat Semua &rarr;</a>
                </div>
                <div class="space-y-3">
                    @forelse($upcomingProkers as $proker)
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-indigo-50/50 transition-colors">
                        <div class="flex-1 min-w-0 mr-3">
                            <p class="font-bold text-slate-800 text-sm truncate">{{ $proker->nama }}</p>
                            <div class="flex items-center gap-2 mt-1 text-xs text-slate-500">
                                <span class="font-semibold text-indigo-600 truncate">{{ $proker->organisasi->singkatan ?? $proker->organisasi->nama ?? '-' }}</span>
                                <span>&bull;</span>
                                <span class="shrink-0">{{ $proker->tanggal_mulai ? $proker->tanggal_mulai->translatedFormat('d M Y') : '-' }}</span>
                            </div>
                        </div>
                        <span class="inline-flex flex-shrink-0 items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                            {{ $proker->status === 'rencana' ? 'bg-slate-200 text-slate-700' : '' }}
                            {{ $proker->status === 'berjalan' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $proker->status === 'selesai' ? 'bg-emerald-100 text-emerald-700' : '' }}
                            {{ $proker->status === 'dibatalkan' ? 'bg-rose-100 text-rose-700' : '' }}">
                            {{ $proker->status }}
                        </span>
                    </div>
                    @empty
                    <p class="text-sm text-slate-500 text-center py-6">Belum ada program kerja.</p>
                    @endforelse
                </div>
            </div>

            <!-- Organisasi Teraktif -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 min-w-0">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-2 h-4 bg-amber-500 rounded-full"></div>
                    <h3 class="text-base font-bold text-slate-800">Organisasi Teraktif</h3>
                </div>
                <ul class="space-y-3">
                    @forelse($leaderboard as $idx => $org)
                    <li class="flex items-center justify-between p-3 rounded-xl bg-slate-50">
                        <div class="flex items-center gap-3 min-w-0 mr-2">
                            <div class="w-8 h-8 rounded-full font-bold text-sm flex items-center justify-center shrink-0
                                {{ $idx === 0 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $idx + 1 }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-slate-800 truncate">{{ $org->singkatan ?? $org->nama }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ $org->tipe }}</p>
                            </div>
                        </div>
                        <span class="inline-flex shrink-0 items-center justify-center px-2.5 py-1 text-xs font-bold bg-indigo-50 text-indigo-700 rounded-lg">
                            {{ $org->program_kerja_count }} Proker
                        </span>
                    </li>
                    @empty
                    <li class="text-sm text-slate-500 text-center py-6">Belum ada data organisasi.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:navigated', function() {
            var el = document.querySelector("#chart-pengaduan");
            if (!el || el.dataset.rendered) return;

            var bulanList = @json($bulanList);
            var masukChart = @json($pengaduanMasukChart);
            var selesaiChart = @json($pengaduanSelesaiChart);

            // Use clean 3-letter month labels for x-axis (Apr, Mei, Jun, Jul, Agt, Sep)
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
                    height: 260,
                    width: '100%',
                    fontFamily: 'Inter, sans-serif',
                    toolbar: { show: false },
                    zoom: { enabled: false },
                    parentHeightOffset: 0,
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 500
                    }
                },
                colors: ['#6366f1', '#10b981'],
                dataLabels: { enabled: false },
                stroke: {
                    curve: 'smooth',
                    width: [3.5, 3.5],
                    lineCap: 'round'
                },
                markers: {
                    size: 5,
                    colors: ['#ffffff', '#ffffff'],
                    strokeColors: ['#6366f1', '#10b981'],
                    strokeWidth: 3,
                    hover: {
                        size: 8,
                        sizeOffset: 3
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        type: 'vertical',
                        shadeIntensity: 1,
                        opacityFrom: 0.28,
                        opacityTo: 0.02,
                        stops: [0, 95, 100]
                    }
                },
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4,
                    xaxis: { lines: { show: false } },
                    yaxis: { lines: { show: true } },
                    padding: {
                        top: 5,
                        right: 25,
                        bottom: 0,
                        left: 15
                    }
                },
                xaxis: {
                    type: 'category',
                    categories: bulanShort,
                    tickPlacement: 'on',
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: {
                        rotate: 0,
                        trim: false,
                        style: {
                            colors: '#94a3b8',
                            fontSize: '12px',
                            fontFamily: 'Inter, sans-serif',
                            fontWeight: 600
                        }
                    },
                    tooltip: { enabled: false }
                },
                yaxis: {
                    labels: {
                        formatter: function(val) { return Math.floor(val) },
                        style: {
                            colors: '#94a3b8',
                            fontSize: '11px',
                            fontFamily: 'Inter, sans-serif',
                            fontWeight: 500
                        }
                    },
                    min: 0,
                    forceNiceScale: true
                },
                legend: {
                    show: false
                },
                tooltip: {
                    theme: 'light',
                    shared: true,
                    intersect: false,
                    style: {
                        fontSize: '12px',
                        fontFamily: 'Inter, sans-serif'
                    },
                    x: {
                        formatter: function(val, opts) {
                            var idx = opts ? opts.dataPointIndex : -1;
                            return (idx >= 0 && bulanList[idx]) ? bulanList[idx] : val;
                        }
                    },
                    y: {
                        formatter: function(val) {
                            return val + ' Pengaduan';
                        }
                    }
                }
            };

            function renderChart() {
                if (typeof window.ApexCharts === 'undefined') return;

                requestAnimationFrame(function() {
                    el.innerHTML = '';
                    var w = Math.floor(el.clientWidth || el.getBoundingClientRect().width);
                    if (w > 0) {
                        optionsPengaduan.chart.width = w;
                    }

                    if (window.__chartPengaduan) {
                        try { window.__chartPengaduan.destroy(); } catch(e) {}
                    }
                    window.__chartPengaduan = new window.ApexCharts(el, optionsPengaduan);
                    window.__chartPengaduan.render();
                    el.dataset.rendered = 'true';
                });
            }

            if (typeof window.ApexCharts !== 'undefined') {
                renderChart();
            } else {
                var s = document.createElement('script');
                s.src = 'https://cdn.jsdelivr.net/npm/apexcharts';
                s.onload = renderChart;
                document.head.appendChild(s);
            }

            if (window.ResizeObserver && el.parentElement) {
                var ro = new ResizeObserver(function(entries) {
                    for (var i = 0; i < entries.length; i++) {
                        var newW = Math.floor(entries[i].contentRect.width);
                        if (newW > 0 && window.__chartPengaduan && Math.abs(newW - (optionsPengaduan.chart.width || 0)) > 5) {
                            optionsPengaduan.chart.width = newW;
                            window.__chartPengaduan.updateOptions({ chart: { width: newW } });
                        }
                    }
                });
                ro.observe(el.parentElement);
            }
        });

        // Also run on initial page load
        document.addEventListener('DOMContentLoaded', function() {
            var event = new Event('livewire:navigated');
            document.dispatchEvent(event);
        });
    </script>
</div>
