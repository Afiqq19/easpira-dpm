<?php

namespace App\Livewire\Organisasi;

use App\Models\ProgramKerja;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class KelolaProker extends Component
{
    use WithPagination;

    public $search = '';
    public $searchOrg = '';
    public $selectedOrganisasi = null;
    public $selectedOrganisasiNama = '';
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;

    public $proker_id, $nama, $deskripsi, $tanggal_mulai, $tanggal_selesai, $organisasi_id;
    public $status = 'rencana';
    public $kategori = 'akademik';
    public $kategori_lainnya = '';
    public $is_active = true;

    public $prokerToDelete = null;

    protected $messages = [
        'nama.required' => 'Nama program kerja tidak boleh kosong.',
        'status.required' => 'Status harus dipilih.',
        'kategori.required' => 'Kategori harus dipilih.',
        'kategori_lainnya.required_if' => 'Kategori lainnya wajib diisi jika kategori "Lainnya" dipilih.',
        'organisasi_id.required' => 'Organisasi harus dipilih.',
        'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
    ];

    public function mount()
    {
        $user = auth()->user();
        if (!$user->hasRole(['admin', 'staff_dewan'])) {
            $this->selectedOrganisasi = $user->organisasi_id;
            $this->selectedOrganisasiNama = $user->organisasi->singkatan ?? $user->organisasi->nama ?? '';
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSearchOrg()
    {
        // For search org filtering
    }

    public function pilihOrganisasi($id)
    {
        $org = \App\Models\Organisasi::findOrFail($id);
        $this->selectedOrganisasi = $id;
        $this->selectedOrganisasiNama = $org->singkatan ?? $org->nama;
        $this->resetPage();
    }

    public function kembaliKeOrganisasi()
    {
        $this->selectedOrganisasi = null;
        $this->selectedOrganisasiNama = '';
        $this->search = '';
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();
        
        $organisasis = null;
        $prokers = null;

        if ($user->hasRole(['admin', 'staff_dewan']) && !$this->selectedOrganisasi) {
            $orgQuery = \App\Models\Organisasi::where('is_active', true)
                ->withCount('programKerja');
                
            if ($this->searchOrg) {
                $orgQuery->where(function($q) {
                    $q->where('nama', 'like', '%' . $this->searchOrg . '%')
                      ->orWhere('singkatan', 'like', '%' . $this->searchOrg . '%');
                });
            }

            $organisasis = $orgQuery->orderBy('tipe')->orderBy('nama')->get();
        } else {
            $query = ProgramKerja::query();
            
            $query->where('organisasi_id', $this->selectedOrganisasi);

            if ($this->search) {
                $query->where(function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%')
                      ->orWhere('kategori', 'like', '%' . $this->search . '%');
                });
            }

            $prokers = $query->with('organisasi')->latest()->paginate(10);
        }
        
        $allOrganisasis = \App\Models\Organisasi::where('is_active', true)->get();

        return view('livewire.organisasi.kelola-proker', compact('prokers', 'organisasis', 'allOrganisasis'));
    }

    public function create()
    {
        $this->resetFields();
        $this->organisasi_id = $this->selectedOrganisasi;
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $this->resetFields();
        $user = auth()->user();
        
        $query = ProgramKerja::query();
        if (!$user->hasRole(['admin', 'staff_dewan'])) {
            $query->where('organisasi_id', $user->organisasi_id);
        }
        
        $proker = $query->findOrFail($id);
        
        $this->proker_id = $proker->id;
        $this->nama = $proker->nama;
        $this->deskripsi = $proker->deskripsi;
        $this->tanggal_mulai = $proker->tanggal_mulai;
        $this->tanggal_selesai = $proker->tanggal_selesai;
        $this->status = $proker->status;
        $this->kategori = $proker->kategori;
        $this->kategori_lainnya = $proker->kategori_lainnya;
        $this->is_active = $proker->is_active;
        $this->organisasi_id = $proker->organisasi_id;

        $this->isModalOpen = true;
    }

    public function save()
    {
        $user = auth()->user();
        
        $rules = [
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:rencana,berjalan,selesai,dibatalkan',
            'kategori' => 'required|in:akademik,sosial,olahraga,seni,lainnya',
            'kategori_lainnya' => 'required_if:kategori,lainnya|nullable|string|max:255',
            'organisasi_id' => 'required|exists:organisasi,id',
        ];

        $this->validate($rules);
        
        $org_id = $this->organisasi_id;

        ProgramKerja::updateOrCreate(
            ['id' => $this->proker_id],
            [
                'nama' => $this->nama,
                'deskripsi' => $this->deskripsi,
                'tanggal_mulai' => $this->tanggal_mulai,
                'tanggal_selesai' => $this->tanggal_selesai,
                'status' => $this->status,
                'kategori' => $this->kategori,
                'kategori_lainnya' => $this->kategori === 'lainnya' ? $this->kategori_lainnya : null,
                'is_active' => $this->is_active,
                'organisasi_id' => $org_id,
                'user_id' => auth()->id(),
            ]
        );

        $this->isModalOpen = false;
        session()->flash('message', $this->proker_id ? 'Program kerja berhasil diperbarui.' : 'Program kerja berhasil ditambahkan.');
    }

    public function confirmDelete($id)
    {
        $user = auth()->user();
        $query = ProgramKerja::query();
        if (!$user->hasRole(['admin', 'staff_dewan'])) {
            $query->where('organisasi_id', $user->organisasi_id);
        }
        $this->prokerToDelete = $query->findOrFail($id);
        $this->isDeleteModalOpen = true;
    }

    public function delete()
    {
        if ($this->prokerToDelete) {
            $this->prokerToDelete->delete();
            $this->isDeleteModalOpen = false;
            session()->flash('message', 'Program kerja berhasil dihapus.');
        }
    }

    public function changeStatus($id, $status)
    {
        $user = auth()->user();
        $query = ProgramKerja::query();
        if (!$user->hasRole(['admin', 'staff_dewan'])) {
            $query->where('organisasi_id', $user->organisasi_id);
        }
        $proker = $query->findOrFail($id);
        
        $proker->status = $status;
        $proker->save();
        
        session()->flash('message', 'Status program kerja berhasil diubah.');
    }



    private function resetFields()
    {
        $this->proker_id = null;
        $this->nama = '';
        $this->deskripsi = '';
        $this->tanggal_mulai = null;
        $this->tanggal_selesai = null;
        $this->status = 'rencana';
        $this->kategori = 'akademik';
        $this->kategori_lainnya = '';
        $this->is_active = true;
        $this->organisasi_id = null;
        $this->prokerToDelete = null;
    }
}
