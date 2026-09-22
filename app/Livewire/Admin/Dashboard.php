<?php

namespace App\Livewire\Admin;

use Livewire\Component;

use Livewire\Attributes\Layout;
use App\Models\Pengaduan;
use App\Models\ProgramKerja;
use App\Models\Organisasi;
use Carbon\Carbon;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        // 1. STATISTIK UMUM
        $totalPengaduan = Pengaduan::count();
        $pengaduanSelesai = Pengaduan::where('status', 'selesai')->count();
        $pengaduanBaru = Pengaduan::where('status', 'menunggu')->count();
        
        $totalProker = ProgramKerja::count();
        $prokerBerjalan = ProgramKerja::where('status', 'berjalan')->count();
        
        $totalOrganisasi = Organisasi::where('is_active', true)->where('tipe', '!=', 'BEM')->count();

        // 2. DATA CHART: Pengaduan per bulan (6 bulan terakhir)
        $bulanList = [];
        $pengaduanMasukChart = [];
        $pengaduanSelesaiChart = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $bulanList[] = $date->translatedFormat('M Y');
            
            $pengaduanMasukChart[] = Pengaduan::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
                
            $pengaduanSelesaiChart[] = Pengaduan::whereMonth('updated_at', $date->month)
                ->whereYear('updated_at', $date->year)
                ->where('status', 'selesai')
                ->count();
        }

        // 3. DATA CHART: Status Proker
        $prokerStatusChart = [
            ProgramKerja::where('status', 'rencana')->count(),
            ProgramKerja::where('status', 'berjalan')->count(),
            ProgramKerja::where('status', 'selesai')->count(),
            ProgramKerja::where('status', 'dibatalkan')->count(),
        ];

        // 4. LEADERBOARD: Organisasi dengan Proker Terbanyak
        $leaderboard = Organisasi::withCount('programKerja')
            ->orderBy('program_kerja_count', 'desc')
            ->take(5)
            ->get();

        // 5. UPCOMING PROKERS: Proker yang mau jalan / sedang berjalan
        $upcomingProkers = ProgramKerja::with('organisasi')
            ->orderByRaw("FIELD(status, 'berjalan', 'rencana', 'selesai', 'dibatalkan')")
            ->orderBy('tanggal_mulai', 'asc')
            ->take(5)
            ->get();

        return view('livewire.admin.dashboard', compact(
            'totalPengaduan', 'pengaduanSelesai', 'pengaduanBaru',
            'totalProker', 'prokerBerjalan', 'totalOrganisasi',
            'bulanList', 'pengaduanMasukChart', 'pengaduanSelesaiChart',
            'prokerStatusChart', 'leaderboard', 'upcomingProkers'
        ));
    }
}
