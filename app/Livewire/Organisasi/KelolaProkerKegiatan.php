<?php

namespace App\Livewire\Organisasi;

use App\Models\Periode;
use App\Models\ProgramKerja;
use App\Models\Kegiatan;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class KelolaProkerKegiatan extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $periode_id = null;
    
    public $isProkerModalOpen = false;
    public $isKegiatanModalOpen = false;
    public $isDeleteModalOpen = false;
    public $deleteType = ''; // 'proker' or 'kegiatan'
    public $deleteId = null;

    // Proker Form
    public $proker_id, $nama, $deskripsi, $tanggal_mulai, $tanggal_selesai;
    public $status = 'rencana';
    public $kategori = 'akademik';
    public $kategori_lainnya = '';
    public $is_active = true;
    public $file_proposal;
    public $existing_file_proposal;

    // Kegiatan Form
    public $kegiatan_id, $judul, $deskripsi_kegiatan, $tgl_mulai_kegiatan, $tgl_selesai_kegiatan, $lokasi;
    public $kontak_penanggung_jawab, $no_kontak;
    public $is_published = false;
    public $poster, $old_poster;
    public $selected_proker_id; // when adding kegiatan to a proker

    public function mount()
    {
        $user = auth()->user();
        if ($user->organisasi) {
            $this->periode_id = $user->organisasi->active_periode_id;
        }

        if (!$this->periode_id) {
            $firstPeriode = Periode::latest()->first();
            if ($firstPeriode) {
                $this->periode_id = $firstPeriode->id;
            }
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPeriodeId()
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();
        $periodes = Periode::orderBy('id', 'desc')->get();
        
        $query = ProgramKerja::query()
            ->with(['kegiatan' => function($q) {
                $q->orderBy('tanggal_mulai', 'asc');
            }]);
            
        if (!$user->hasRole(['admin', 'staff_dewan'])) {
            $query->where('organisasi_id', $user->organisasi_id);
        }

        if ($this->periode_id) {
            $query->where('periode_id', $this->periode_id);
        }

        $query->where(function ($q) {
            $q->where('nama', 'like', '%' . $this->search . '%')
              ->orWhere('kategori', 'like', '%' . $this->search . '%')
              ->orWhereHas('kegiatan', function($q2) {
                  $q2->where('judul', 'like', '%' . $this->search . '%');
              });
        });

        $prokers = $query->latest()->paginate(10);
        $organisasis = \App\Models\Organisasi::all();
        
        $isReadOnly = true;
        if ($user->organisasi && $user->organisasi->active_periode_id == $this->periode_id) {
            $isReadOnly = false;
        }

        return view('livewire.organisasi.kelola-proker-kegiatan', compact('prokers', 'periodes', 'organisasis', 'isReadOnly'));
    }

    // --- PROKER ACTIONS ---

    public function createProker()
    {
        $this->resetProkerFields();
        $this->isProkerModalOpen = true;
    }

    public function editProker($id)
    {
        $this->resetProkerFields();
        $proker = ProgramKerja::findOrFail($id);
        
        $this->proker_id = $proker->id;
        $this->nama = $proker->nama;
        $this->deskripsi = $proker->deskripsi;
        $this->tanggal_mulai = $proker->tanggal_mulai;
        $this->tanggal_selesai = $proker->tanggal_selesai;
        $this->status = $proker->status;
        $this->kategori = $proker->kategori;
        $this->kategori_lainnya = $proker->kategori_lainnya;
        $this->is_active = $proker->is_active;
        $this->existing_file_proposal = $proker->file_proposal;

        $this->isProkerModalOpen = true;
    }

    public function saveProker()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:rencana,berjalan,selesai,dibatalkan',
            'kategori' => 'required|in:akademik,sosial,olahraga,seni,lainnya',
            'kategori_lainnya' => 'required_if:kategori,lainnya|nullable|string|max:255',
            'file_proposal' => 'nullable|mimes:pdf|max:4096',
        ], [
            'file_proposal.mimes' => 'File proposal harus berformat PDF.',
            'file_proposal.max' => 'Ukuran file proposal maksimal 4MB.',
        ]);
        
        $user = auth()->user();
        $org_id = $user->organisasi_id; // Assume for organisasi role

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
                'periode_id' => $this->periode_id,
            ]
        );
        
        $proker = ProgramKerja::find($this->proker_id ?? ProgramKerja::latest()->first()->id);

        if ($this->file_proposal) {
            if ($proker->file_proposal && \Illuminate\Support\Facades\Storage::disk('public')->exists($proker->file_proposal)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($proker->file_proposal);
            }
            $fileName = uniqid('proposal_') . '.pdf';
            $path = $this->file_proposal->storeAs('proker_proposals', $fileName, 'public');
            $proker->update(['file_proposal' => $path]);
        }

        $this->isProkerModalOpen = false;
        session()->flash('message', $this->proker_id ? 'Program kerja diperbarui.' : 'Program kerja ditambahkan.');
    }

    // --- KEGIATAN ACTIONS ---

    public function createKegiatan($prokerId)
    {
        $this->resetKegiatanFields();
        $this->selected_proker_id = $prokerId;
        $this->isKegiatanModalOpen = true;
    }

    public function editKegiatan($id)
    {
        $this->resetKegiatanFields();
        $kegiatan = Kegiatan::findOrFail($id);
        
        $this->kegiatan_id = $kegiatan->id;
        $this->judul = $kegiatan->judul;
        $this->deskripsi_kegiatan = $kegiatan->deskripsi;
        $this->tgl_mulai_kegiatan = $kegiatan->tanggal_mulai ? $kegiatan->tanggal_mulai->format('Y-m-d\TH:i') : null;
        $this->tgl_selesai_kegiatan = $kegiatan->tanggal_selesai ? $kegiatan->tanggal_selesai->format('Y-m-d\TH:i') : null;
        $this->lokasi = $kegiatan->lokasi;
        $this->kontak_penanggung_jawab = $kegiatan->kontak_penanggung_jawab;
        $this->no_kontak = $kegiatan->no_kontak;
        $this->is_published = (bool) $kegiatan->is_published;
        $this->old_poster = $kegiatan->poster;
        $this->selected_proker_id = $kegiatan->program_kerja_id;

        $this->isKegiatanModalOpen = true;
    }

    public function saveKegiatan()
    {
        $this->validate([
            'judul' => 'required|string|max:255',
            'deskripsi_kegiatan' => 'required|string',
            'tgl_mulai_kegiatan' => 'required|date',
            'tgl_selesai_kegiatan' => 'nullable|date|after_or_equal:tgl_mulai_kegiatan',
            'lokasi' => 'required|string|max:255',
            'poster' => 'nullable|image|max:2048',
        ], [
            'judul.required' => 'Judul kegiatan harus diisi.',
            'deskripsi_kegiatan.required' => 'Deskripsi harus diisi.',
            'tgl_mulai_kegiatan.required' => 'Tanggal mulai harus diisi.',
            'lokasi.required' => 'Lokasi harus diisi.',
        ]);
        
        $user = auth()->user();
        $org_id = $user->organisasi_id;

        $data = [
            'judul' => $this->judul,
            'deskripsi' => $this->deskripsi_kegiatan,
            'tanggal_mulai' => $this->tgl_mulai_kegiatan,
            'tanggal_selesai' => $this->tgl_selesai_kegiatan ?: null,
            'lokasi' => $this->lokasi,
            'kontak_penanggung_jawab' => $this->kontak_penanggung_jawab,
            'no_kontak' => $this->no_kontak,
            'is_published' => $this->is_published,
            'organisasi_id' => $org_id,
            'program_kerja_id' => $this->selected_proker_id,
            'user_id' => auth()->id(),
        ];

        if ($this->poster) {
            $data['poster'] = $this->poster->store('posters', 'public');
        }

        Kegiatan::updateOrCreate(['id' => $this->kegiatan_id], $data);

        $this->isKegiatanModalOpen = false;
        session()->flash('message', $this->kegiatan_id ? 'Kegiatan diperbarui.' : 'Kegiatan ditambahkan.');
    }

    // --- DELETE ACTIONS ---

    public function confirmDelete($type, $id)
    {
        $this->deleteType = $type;
        $this->deleteId = $id;
        $this->isDeleteModalOpen = true;
    }

    public function delete()
    {
        if ($this->deleteType === 'proker') {
            $proker = ProgramKerja::find($this->deleteId);
            if ($proker) {
                if ($proker->file_proposal && \Illuminate\Support\Facades\Storage::disk('public')->exists($proker->file_proposal)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($proker->file_proposal);
                }
                $proker->delete();
            }
            session()->flash('message', 'Program kerja berhasil dihapus.');
        } else {
            $keg = Kegiatan::find($this->deleteId);
            if ($keg) {
                if ($keg->poster && \Illuminate\Support\Facades\Storage::disk('public')->exists($keg->poster)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($keg->poster);
                }
                $keg->delete();
            }
            session()->flash('message', 'Kegiatan berhasil dihapus.');
        }
        
        $this->isDeleteModalOpen = false;
    }

    private function resetProkerFields()
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
        $this->file_proposal = null;
        $this->existing_file_proposal = null;
        $this->resetErrorBag();
    }

    private function resetKegiatanFields()
    {
        $this->kegiatan_id = null;
        $this->judul = '';
        $this->deskripsi_kegiatan = '';
        $this->tgl_mulai_kegiatan = null;
        $this->tgl_selesai_kegiatan = null;
        $this->lokasi = '';
        $this->kontak_penanggung_jawab = '';
        $this->no_kontak = '';
        $this->is_published = false;
        $this->poster = null;
        $this->old_poster = null;
        $this->selected_proker_id = null;
        $this->resetErrorBag();
    }
}
