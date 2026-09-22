<?php

namespace App\Livewire\Admin;

use App\Models\Organisasi;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class MasterOrganisasi extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $isOpen = false;
    public $isDeleteModalOpen = false;
    public $deleteId = null;

    // Form fields
    public $organisasi_id;
    public $nama;
    public $singkatan;
    public $tipe = 'HMPS';
    public $deskripsi;
    public $is_active = true;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetFields();
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $this->resetFields();
        $org = Organisasi::findOrFail($id);
        
        $this->organisasi_id = $org->id;
        $this->nama = $org->nama;
        $this->singkatan = $org->singkatan;
        $this->tipe = $org->tipe;
        $this->deskripsi = $org->deskripsi;
        $this->is_active = $org->is_active;

        $this->isOpen = true;
    }

    public function save()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'singkatan' => 'nullable|string|max:50',
            'tipe' => 'required|in:HMPS,UKM,DPM',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        Organisasi::updateOrCreate(
            ['id' => $this->organisasi_id],
            [
                'nama' => $this->nama,
                'singkatan' => $this->singkatan,
                'tipe' => $this->tipe,
                'deskripsi' => $this->deskripsi,
                'is_active' => $this->is_active,
            ]
        );

        $this->isOpen = false;
        session()->flash('message', $this->organisasi_id ? 'Organisasi berhasil diperbarui.' : 'Organisasi berhasil ditambahkan.');
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->isDeleteModalOpen = true;
    }

    public function delete()
    {
        $org = Organisasi::findOrFail($this->deleteId);
        $org->delete();
        
        $this->isDeleteModalOpen = false;
        session()->flash('message', 'Organisasi berhasil dihapus.');
    }

    private function resetFields()
    {
        $this->organisasi_id = null;
        $this->nama = '';
        $this->singkatan = '';
        $this->tipe = 'HMPS';
        $this->deskripsi = '';
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        $organisasis = Organisasi::where('nama', 'like', '%' . $this->search . '%')
            ->orWhere('singkatan', 'like', '%' . $this->search . '%')
            ->orderBy('tipe')
            ->orderBy('nama')
            ->paginate(10);

        return view('livewire.admin.master-organisasi', compact('organisasis'));
    }
}
