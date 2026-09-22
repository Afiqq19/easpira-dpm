<?php

namespace App\Livewire\Publik;

use App\Models\Kegiatan;
use Livewire\Component;
use Livewire\WithPagination;

class DaftarKegiatan extends Component
{
    use WithPagination;

    public $filter = 'akan_datang'; // semua, akan_datang, selesai

    public function render()
    {
        $query = Kegiatan::published()
            ->with('organisasi')
            ->orderBy('tanggal_mulai', 'asc'); // Ascending to show upcoming first

        if ($this->filter === 'akan_datang') {
            $query->where('tanggal_mulai', '>=', now());
        } elseif ($this->filter === 'selesai') {
            $query->where('tanggal_mulai', '<', now());
            $query->orderBy('tanggal_mulai', 'desc'); // If past events, show latest first
        }

        $kegiatans = $query->paginate(6);

        return view('livewire.publik.daftar-kegiatan', compact('kegiatans'));
    }
    
    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }
}
