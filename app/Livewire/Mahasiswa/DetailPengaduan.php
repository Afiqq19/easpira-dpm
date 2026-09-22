<?php

namespace App\Livewire\Mahasiswa;

use App\Models\Pengaduan;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class DetailPengaduan extends Component
{
    public $ticket_code;
    public $pengaduan;

    public function mount($ticket_code)
    {
        $this->ticket_code = $ticket_code;
        
        $pengaduan = Pengaduan::with(['kategori', 'tanggapansPublik.user'])
            ->where('ticket_code', $ticket_code)
            ->first();

        if (!$pengaduan) {
            abort(404, 'Nomor tiket tidak ditemukan.');
        }

        // Validasi kepemilikan tiket: Hanya pembuat laporan yang boleh melihat
        $isOwner = ($pengaduan->user_id == Auth::id());

        // Jika user_id masih null (tiket anonim lama), cek apakah milik user yang sedang login
        if (!$isOwner && is_null($pengaduan->user_id) && $pengaduan->mode_privasi === 'anonim') {
            try {
                $enkripsiService = app(\App\Services\EnkripsiIdentitasService::class);
                $identitas = $enkripsiService->bukaIdentitas($pengaduan);
                if ($identitas && isset($identitas['user_id']) && $identitas['user_id'] == Auth::id()) {
                    $pengaduan->user_id = Auth::id();
                    $pengaduan->saveQuietly();
                    $isOwner = true;
                }
            } catch (\Throwable $e) {
                // Ignore
            }
        }

        if (!$isOwner) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk mengakses tiket pengaduan ini.');
        }

        $this->pengaduan = $pengaduan;
    }

    public $isi_tanggapan;

    public function balasTanggapan()
    {
        $this->validate([
            'isi_tanggapan' => 'required|string|min:5',
        ], [
            'isi_tanggapan.required' => 'Tanggapan tidak boleh kosong.',
            'isi_tanggapan.min' => 'Tanggapan minimal 5 karakter.',
        ]);

        \App\Models\TanggapanPengaduan::create([
            'pengaduan_id' => $this->pengaduan->id,
            'user_id' => Auth::id(),
            'isi_tanggapan' => $this->isi_tanggapan,
            'tipe' => 'mahasiswa',
            'is_internal' => false,
        ]);

        // Refresh tanggapan
        $this->pengaduan->refresh();
        $this->isi_tanggapan = '';
        
        session()->flash('success_tanggapan', 'Tanggapan Anda berhasil dikirim!');
    }

    public function render()
    {
        return view('livewire.mahasiswa.detail-pengaduan');
    }
}
