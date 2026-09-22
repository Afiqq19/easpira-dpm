<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Dashboard Organisasi</h2>
            <p class="text-sm text-slate-500 mt-1">Pantau perkembangan program kerja dan evaluasi dari mahasiswa.</p>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Stat: Total Proker -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 flex flex-col relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-indigo-50 rounded-full opacity-50 pointer-events-none"></div>
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total Proker</p>
                    <h3 class="text-3xl font-black text-slate-800">{{ $totalProker }}</h3>
                </div>
            </div>
            <p class="text-sm text-slate-600 font-medium">Dimiliki organisasi ini</p>
        </div>

        <!-- Stat: Proker Berjalan -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 flex flex-col relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-50 rounded-full opacity-50 pointer-events-none"></div>
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Sedang Berjalan</p>
                    <h3 class="text-3xl font-black text-slate-800">{{ $prokerBerjalan }}</h3>
                </div>
            </div>
            <p class="text-sm text-slate-600 font-medium">Dalam masa evaluasi aktif</p>
        </div>

        <!-- Stat: Total Evaluasi -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 flex flex-col relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-rose-50 rounded-full opacity-50 pointer-events-none"></div>
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-2xl flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Kritik Masuk</p>
                    <h3 class="text-3xl font-black text-slate-800">{{ $totalEvaluasi }}</h3>
                </div>
            </div>
            <p class="text-sm text-slate-600 font-medium">Total ulasan mahasiswa</p>
        </div>

        <!-- Stat: Rating Rata-rata -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 flex flex-col relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-amber-50 rounded-full opacity-50 pointer-events-none"></div>
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-amber-100 text-amber-500 rounded-2xl flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Rata-rata Rating</p>
                    <h3 class="text-3xl font-black text-slate-800">{{ number_format($avgRating, 1) }}</h3>
                </div>
            </div>
            <p class="text-sm text-slate-600 font-medium">Dari skala 1-5 Bintang</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Main Line Chart -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Ulasan Masuk (6 Bulan Terakhir)</h3>
            <div id="chart-evaluasi" wire:ignore class="w-full h-80"></div>
        </div>
        
        <!-- Radar Chart -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Distribusi Aspek Kritik</h3>
            <div id="chart-aspek" wire:ignore class="flex justify-center h-80"></div>
        </div>
    </div>

    <!-- Recent Evaluasi -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-800">5 Ulasan Terbaru</h3>
            <a href="{{ route('organisasi.evaluasi-proker.index') }}" wire:navigate class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">Lihat Semua &rarr;</a>
        </div>
        <div class="space-y-4">
            @forelse($recentEvaluasi as $eval)
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-start gap-4">
                <div class="shrink-0 w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm">
                    {{ $eval->rating }}★
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-1">
                        <h4 class="font-bold text-slate-800 text-sm">{{ $eval->programKerja->nama }}</h4>
                        <span class="text-xs font-semibold px-2 py-1 bg-slate-200 text-slate-600 rounded-md uppercase tracking-wider">{{ $eval->aspek }}</span>
                    </div>
                    <p class="text-sm text-slate-600 mb-2">"{{ Str::limit($eval->komentar, 100) }}"</p>
                    <p class="text-xs text-slate-400 font-medium">Dari: {{ $eval->is_anonim ? 'Anonim' : ($eval->user->name ?? 'Mahasiswa') }} • {{ $eval->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-8">
                <p class="text-slate-500">Belum ada evaluasi yang masuk untuk program kerja Anda.</p>
            </div>
            @endforelse
        </div>
    </div>

    @script
    <script>
        // Data Chart Evaluasi Line
        const bulanList = @json($bulanList);
        const evalChart = @json($evaluasiChart);
        
        const optionsEvaluasi = {
            series: [{
                name: 'Kritik & Saran',
                data: evalChart.reverse()
            }],
            chart: {
                height: 320,
                type: 'bar',
                fontFamily: 'Inter, sans-serif',
                toolbar: { show: false },
                borderRadius: 4,
            },
            colors: ['#4f46e5'], // Indigo 600
            dataLabels: { enabled: false },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: '40%',
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
        };

        const chartEval = new window.ApexCharts(document.querySelector("#chart-evaluasi"), optionsEvaluasi);
        chartEval.render();

        // Data Chart Aspek (Polar Area)
        const aspekLabels = @json($aspekLabels);
        const aspekData = @json($aspekChart);
        
        const optionsAspek = {
            series: aspekData,
            labels: aspekLabels,
            chart: {
                type: 'polarArea',
                fontFamily: 'Inter, sans-serif',
                height: 320
            },
            stroke: {
                colors: ['#fff']
            },
            fill: {
                opacity: 0.8
            },
            colors: ['#0ea5e9', '#10b981', '#f59e0b', '#8b5cf6', '#64748b'], // Sky, Emerald, Amber, Violet, Slate
            legend: {
                position: 'bottom'
            }
        };

        const chartAspek = new window.ApexCharts(document.querySelector("#chart-aspek"), optionsAspek);
        chartAspek.render();
    </script>
    @endscript
</div>
