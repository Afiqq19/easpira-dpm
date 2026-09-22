<?php

namespace App\Livewire\Publik;

use App\Models\ProgramKerja;
use Livewire\Component;

class DaftarProker extends Component
{
    public $selectedProker = null;
    public $isModalOpen = false;
    public $filterTipe = ''; // BEM, HMPS, UKM atau kosong = semua

    public function showDetail($id)
    {
        $this->selectedProker = ProgramKerja::with('organisasi')->find($id);
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->selectedProker = null;
    }

    public function render()
    {
        // Ambil proker yang aktif dan belum selesai, urutkan dari yang terbaru, batasi 6 saja untuk landing page
        $prokers = ProgramKerja::with('organisasi')
                    ->where('is_active', true)
                    ->where('status', '!=', 'selesai')
                    ->when($this->filterTipe, function($q) {
                        $q->whereHas('organisasi', function($q2) {
                            $q2->where('tipe', $this->filterTipe)
                               ->orWhere('nama', 'like', '%' . $this->filterTipe . '%');
                        });
                    })
                    ->latest()
                    ->take(6)
                    ->get();

        return view('livewire.publik.daftar-proker', compact('prokers'));
    }
}
