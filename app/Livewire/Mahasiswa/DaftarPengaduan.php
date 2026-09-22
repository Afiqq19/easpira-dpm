<?php

namespace App\Livewire\Mahasiswa;

use App\Models\Pengaduan;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class DaftarPengaduan extends Component
{
    use WithPagination;
    public $searchTicketCode = '';

    public function lacakAnonim()
    {
        $this->validate([
            'searchTicketCode' => 'required|string|starts_with:PLP-',
        ], [
            'searchTicketCode.required' => 'Nomor tiket wajib diisi.',
            'searchTicketCode.starts_with' => 'Format nomor tiket tidak valid (harus diawali PLP-).',
        ]);

        // Cek apakah tiket ada
        $ticket = Pengaduan::where('ticket_code', trim($this->searchTicketCode))->first();

        if (!$ticket) {
            $this->addError('searchTicketCode', 'Nomor tiket tidak ditemukan.');
            return;
        }

        // Cek apakah tiket ini milik akun yang sedang login
        $isOwner = ($ticket->user_id == Auth::id());
        if (!$isOwner && is_null($ticket->user_id) && $ticket->mode_privasi === 'anonim') {
            try {
                $enkripsiService = app(\App\Services\EnkripsiIdentitasService::class);
                $identitas = $enkripsiService->bukaIdentitas($ticket);
                if ($identitas && isset($identitas['user_id']) && $identitas['user_id'] == Auth::id()) {
                    $ticket->user_id = Auth::id();
                    $ticket->saveQuietly();
                    $isOwner = true;
                }
            } catch (\Throwable $e) {}
        }

        if (!$isOwner) {
            $this->addError('searchTicketCode', 'Akses ditolak: Tiket ini bukan milik akun Anda.');
            return;
        }

        return redirect()->route('mahasiswa.pengaduan.detail', $ticket->ticket_code);
    }

    public function render()
    {
        // Auto-link tiket anonim lama yang user_id nya masih null
        try {
            $unlinked = Pengaduan::whereNull('user_id')->where('mode_privasi', 'anonim')->get();
            if ($unlinked->isNotEmpty()) {
                $enkripsiService = app(\App\Services\EnkripsiIdentitasService::class);
                foreach ($unlinked as $un) {
                    $data = $enkripsiService->bukaIdentitas($un);
                    if ($data && !empty($data['user_id'])) {
                        $un->user_id = $data['user_id'];
                        if (empty($un->kode_anonim)) {
                            $un->kode_anonim = Pengaduan::generateKodeAnonim();
                        }
                        $un->saveQuietly();
                    }
                }
            }
        } catch (\Throwable $e) {}

        return view('livewire.mahasiswa.daftar-pengaduan', [
            'pengaduanUmum' => Pengaduan::where('user_id', Auth::id())->latest()->paginate(10),
        ]);
    }
}

