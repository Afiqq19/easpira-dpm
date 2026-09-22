<?php

namespace App\Livewire\Eksekutif;

use App\Models\Organisasi;
use App\Models\ProgramKerja;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class PantauProker extends Component
{
    use WithPagination;

    public $search = '';
    public $searchOrg = '';
    public $selectedOrganisasi = null;
    public $selectedOrganisasiNama = '';
    public $statusFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSearchOrg()
    {
        // No pagination for orgs currently, just re-render
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function pilihOrganisasi($id)
    {
        $org = Organisasi::findOrFail($id);
        $this->selectedOrganisasi = $id;
        $this->selectedOrganisasiNama = $org->singkatan ?? $org->nama;
        $this->resetPage();
    }

    public function kembaliKeOrganisasi()
    {
        $this->selectedOrganisasi = null;
        $this->selectedOrganisasiNama = '';
        $this->search = '';
        $this->statusFilter = '';
        $this->resetPage();
    }

    public function render()
    {
        // Daftar organisasi
        $orgQuery = Organisasi::where('is_active', true)
            ->withCount('programKerja');
            
        if ($this->searchOrg) {
            $orgQuery->where(function($q) {
                $q->where('nama', 'like', '%' . $this->searchOrg . '%')
                  ->orWhere('singkatan', 'like', '%' . $this->searchOrg . '%');
            });
        }

        $organisasis = $orgQuery->orderBy('tipe')
            ->orderBy('nama')
            ->get();

        $prokers = null;

        if ($this->selectedOrganisasi) {
            $query = ProgramKerja::where('organisasi_id', $this->selectedOrganisasi)
                ->with('organisasi');

            if ($this->search) {
                $query->where('nama', 'like', '%' . $this->search . '%');
            }

            if ($this->statusFilter) {
                $query->where('status', $this->statusFilter);
            }

            $prokers = $query->latest()->paginate(10);
        }

        return view('livewire.eksekutif.pantau-proker', compact('organisasis', 'prokers'));
    }
}
