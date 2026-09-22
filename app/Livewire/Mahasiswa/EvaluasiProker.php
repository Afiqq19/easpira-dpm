<?php

namespace App\Livewire\Mahasiswa;

use App\Models\EvaluasiProker as EvaluasiModel;
use App\Models\ProgramKerja;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class EvaluasiProker extends Component
{
    use WithPagination;

    public $search = '';
    public $filterOrganisasi = '';
    
    public $isModalOpen = false;
    
    // Form fields
    public $proker_id;
    public $rating = 5;
    public $komentar = '';
    public $aspek = 'pelaksanaan';
    public $aspek_lainnya = '';
    public $is_anonim = false;
    public $selectedProker = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingFilterOrganisasi()
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();
        
        $prokers = ProgramKerja::with('organisasi')
            ->where('is_active', true)
            ->when($user->hasRole(['hmps', 'ukm']), function($q) {
                // HMPS & UKM hanya bisa evaluasi BEM
                $q->whereHas('organisasi', function($q2) {
                    $q2->where('nama', 'like', '%BEM%')->orWhere('tipe', 'BEM');
                });
            })
            // Staff Dewan bisa evaluasi SEMUA proker (BEM + HMPS + UKM) — tidak ada filter
            ->when($this->search, function($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                  ->orWhereHas('organisasi', function($q2) {
                      $q2->where('nama', 'like', '%' . $this->search . '%')
                         ->orWhere('singkatan', 'like', '%' . $this->search . '%');
                  });
            })
            ->when($this->filterOrganisasi, function($q) {
                $q->where('organisasi_id', $this->filterOrganisasi);
            })
            ->latest()
            ->paginate(12);
            
        // Ambil daftar organisasi yang punya proker aktif (untuk dropdown filter)
        $orgQuery = \App\Models\Organisasi::whereHas('programKerja', function($q) {
            $q->where('is_active', true);
        });
        
        // HMPS/UKM hanya bisa filter ke BEM
        if ($user->hasRole(['hmps', 'ukm'])) {
            $orgQuery->where(function($q) {
                $q->where('nama', 'like', '%BEM%')->orWhere('tipe', 'BEM');
            });
        }
        // Staff Dewan: semua organisasi tampil di dropdown filter
        
        $organisasis = $orgQuery->get();

        // Ambil daftar proker_id yang sudah dievaluasi user ini
        $evaluatedProkerIds = EvaluasiModel::where('user_id', $user->id)->pluck('program_kerja_id')->toArray();

        return view('livewire.mahasiswa.evaluasi-proker', compact('prokers', 'organisasis', 'user', 'evaluatedProkerIds'));
    }

    public function bukaModalEvaluasi($id)
    {
        $this->selectedProker = ProgramKerja::with('organisasi')->findOrFail($id);
        $this->proker_id = $id;
        
        // Cek apakah user sudah pernah evaluasi proker ini
        $existingEvaluasi = EvaluasiModel::where('program_kerja_id', $id)
            ->where('user_id', auth()->id())
            ->first();
            
        if ($existingEvaluasi) {
            // Mahasiswa sudah pernah evaluasi proker ini, tolak
            session()->flash('message_error', 'Anda sudah pernah memberikan evaluasi untuk program kerja "' . $this->selectedProker->nama . '". Setiap mahasiswa hanya dapat memberikan 1 evaluasi per program kerja.');
            return;
        }

        $this->rating = 5;
        $this->komentar = '';
        $this->aspek = 'pelaksanaan';
        $this->aspek_lainnya = '';
        $this->is_anonim = false;
        
        $this->isModalOpen = true;
    }

    public function setRating($val)
    {
        $this->rating = $val;
    }

    public function simpanEvaluasi()
    {
        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|min:10',
            'aspek' => 'required|in:pendaftaran,pelaksanaan,manfaat,koordinasi,lainnya',
            'aspek_lainnya' => 'required_if:aspek,lainnya',
            'is_anonim' => 'boolean',
        ], [
            'aspek_lainnya.required_if' => 'Aspek lainnya wajib diisi jika Anda memilih Lainnya.'
        ]);

        $user = auth()->user();

        // Guard: cek ulang duplikasi sebelum simpan
        $sudahAda = EvaluasiModel::where('program_kerja_id', $this->proker_id)
            ->where('user_id', $user->id)
            ->exists();

        if ($sudahAda) {
            $this->isModalOpen = false;
            session()->flash('message_error', 'Anda sudah pernah memberikan evaluasi untuk program kerja ini.');
            return;
        }
        
        $finalKomentar = $this->komentar;
        if ($this->aspek === 'lainnya' && !empty($this->aspek_lainnya)) {
            $finalKomentar = "[Aspek: " . $this->aspek_lainnya . "]\n" . $this->komentar;
        }
        
        EvaluasiModel::create([
            'program_kerja_id' => $this->proker_id,
            'user_id' => $user->id,
            'rating' => $this->rating,
            'komentar' => $finalKomentar,
            'aspek' => $this->aspek,
            'is_anonim' => $this->is_anonim,
        ]);

        $this->isModalOpen = false;
        session()->flash('message', 'Evaluasi Anda berhasil dikirim. Terima kasih atas partisipasinya!');
    }
}
