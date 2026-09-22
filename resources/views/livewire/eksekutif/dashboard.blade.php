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

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Line Chart -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 lg:col-span-2">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Tren Pengaduan (6 Bulan Terakhir)</h3>
            <div id="chart-pengaduan" wire:ignore class="w-full h-80"></div>
        </div>
        
        <!-- Pie Chart & Leaderboard -->
        <div class="space-y-6 lg:col-span-1">
            <!-- Pie Chart -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Status Program Kerja</h3>
                <div id="chart-proker" wire:ignore class="flex justify-center h-48"></div>
            </div>

            <!-- Leaderboard -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Organisasi Teraktif</h3>
                <ul class="space-y-4">
                    @forelse($leaderboard as $idx => $org)
                    <li class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 font-bold text-sm text-slate-500 flex items-center justify-center">
                                {{ $idx + 1 }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ $org->singkatan ?? $org->nama }}</p>
                                <p class="text-xs text-slate-500">{{ $org->tipe }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold bg-indigo-50 text-indigo-700 rounded-lg">
                            {{ $org->program_kerja_count }} Proker
                        </span>
                    </li>
                    @empty
                    <li class="text-sm text-slate-500 text-center py-4">Belum ada data organisasi.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    @script
    <script>
        // Data dari PHP/Livewire ke JS
        const bulanList = @json($bulanList);
        const masukChart = @json($pengaduanMasukChart);
        const selesaiChart = @json($pengaduanSelesaiChart);
        
        // Chart Pengaduan (Line/Area Chart)
        const optionsPengaduan = {
            series: [{
                name: 'Pengaduan Masuk',
                data: masukChart.reverse()
            }, {
                name: 'Pengaduan Selesai',
                data: selesaiChart.reverse()
            }],
            chart: {
                height: 320,
                type: 'area',
                fontFamily: 'Inter, sans-serif',
                toolbar: { show: false },
                zoom: { enabled: false }
            },
            colors: ['#e11d48', '#059669'], // Rose 600, Emerald 600
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: bulanList.reverse(),
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: { formatter: (val) => { return Math.floor(val) } }
            },
            legend: { position: 'top', horizontalAlign: 'right' }
        };

        const chartPengaduan = new window.ApexCharts(document.querySelector("#chart-pengaduan"), optionsPengaduan);
        chartPengaduan.render();

        // Chart Proker (Donut)
        const prokerStatusData = @json($prokerStatusChart);
        const optionsProker = {
            series: prokerStatusData,
            labels: ['Rencana', 'Berjalan', 'Selesai', 'Dibatalkan'],
            chart: {
                type: 'donut',
                fontFamily: 'Inter, sans-serif',
                height: 220
            },
            colors: ['#cbd5e1', '#0ea5e9', '#10b981', '#f43f5e'], // Slate 300, Sky 500, Emerald 500, Rose 500
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            name: { show: false },
                            value: {
                                show: true,
                                fontSize: '24px',
                                fontWeight: 800,
                                color: '#1e293b'
                            },
                            total: {
                                show: true,
                                showAlways: true,
                                label: 'Total',
                                fontSize: '12px',
                                color: '#64748b'
                            }
                        }
                    }
                }
            },
            dataLabels: { enabled: false },
            legend: { show: false },
            stroke: { show: false }
        };

        const chartProker = new window.ApexCharts(document.querySelector("#chart-proker"), optionsProker);
        chartProker.render();
    </script>
    @endscript
</div>
