<?php

namespace App\Livewire\Admin;

use App\Models\Periode;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class KelolaPeriode extends Component
{
    use WithPagination;

    public $search = '';
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;

    public $periode_id, $nama;
    public $is_active = false;

    public $periodeToDelete = null;

    protected $rules = [
        'nama' => 'required|string|max:100',
    ];

    protected $messages = [
        'nama.required' => 'Nama periode harus diisi.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $periodes = Periode::where('nama', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);
            
        return view('livewire.admin.kelola-periode', compact('periodes'));
    }

    public function create()
    {
        $this->resetFields();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $this->resetFields();
        $periode = Periode::findOrFail($id);
        
        $this->periode_id = $periode->id;
        $this->nama = $periode->nama;
        $this->is_active = $periode->is_active;

        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate();

        Periode::updateOrCreate(
            ['id' => $this->periode_id],
            [
                'nama' => $this->nama,
            ]
        );

        $this->isModalOpen = false;
        session()->flash('message', $this->periode_id ? 'Periode berhasil diperbarui.' : 'Periode berhasil ditambahkan.');
    }

    public function setActive($id)
    {
        // Nonaktifkan semua
        Periode::query()->update(['is_active' => false]);
        // Aktifkan yang dipilih
        Periode::findOrFail($id)->update(['is_active' => true]);
        
        session()->flash('message', 'Periode aktif berhasil diubah.');
    }

    public function confirmDelete($id)
    {
        $this->periodeToDelete = Periode::findOrFail($id);
        $this->isDeleteModalOpen = true;
    }

    public function delete()
    {
        if ($this->periodeToDelete) {
            // Cek jika sedang aktif
            if ($this->periodeToDelete->is_active) {
                session()->flash('error', 'Tidak bisa menghapus periode yang sedang aktif.');
            } else {
                $this->periodeToDelete->delete();
                session()->flash('message', 'Periode berhasil dihapus.');
            }
            $this->isDeleteModalOpen = false;
        }
    }

    private function resetFields()
    {
        $this->periode_id = null;
        $this->nama = '';
        $this->is_active = false;
        $this->periodeToDelete = null;
    }
}
