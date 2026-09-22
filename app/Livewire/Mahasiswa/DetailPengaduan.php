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
        
        // Cari pengaduan milik user yang login
        $this->pengaduan = Pengaduan::with(['kategori', 'tanggapansPublik.user'])
            ->where('ticket_code', $ticket_code)
            ->where(function($query) {
                // Pastikan hanya bisa dilihat oleh si pembuat laporan
                $query->where('user_id', Auth::id())
                      // Atau jika anonim, kita cek apakah ada relasi di tabel identitas (butuh join/subquery), 
                      // tapi karena mahasiswa login, pengaduan anonim tidak punya user_id di tabel utama.
                      // Solusi: Kita izinkan jika dia tau ticket_code (seperti resi pengiriman).
                      ->orWhereNull('user_id');
            })
            ->firstOrFail();
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
            'user_id' => Auth::id(), // null jika guest, tapi pelapor sudah login
            'isi_tanggapan' => $this->isi_tanggapan,
            'is_internal' => false, // Mahasiswa tidak bisa bikin internal
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
