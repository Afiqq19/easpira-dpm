<?php

namespace App\Livewire\StaffDewan;

use App\Models\Pengaduan;
use App\Models\TanggapanPengaduan;
use App\Models\User;
use App\Services\EnkripsiIdentitasService;
use App\Traits\MencatatAktivitas;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class DetailPengaduan extends Component
{
    use MencatatAktivitas;

    public $ticket_code;
    public $pengaduan;
    public $isi_tanggapan;
    
    // Status update from detail
    public $status_baru;
    
    // Decrypted Identity
    public $identitasPelapor = null;

    public function mount($ticket_code)
    {
        $this->ticket_code = $ticket_code;
        $this->pengaduan = Pengaduan::with(['kategori', 'tanggapans.user', 'user'])->where('ticket_code', $ticket_code)->firstOrFail();
        
        $this->status_baru = $this->pengaduan->status;
        
        // Identitas pelapor anonim TIDAK DIBUKA secara otomatis.
        $this->identitasPelapor = null;
    }

    public function ubahStatus($status)
    {
        $validStatuses = ['diterima', 'diverifikasi', 'diproses', 'ditindaklanjuti', 'selesai', 'ditolak'];
        if (!in_array($status, $validStatuses)) return;

        $this->pengaduan->status = $status;
        $this->pengaduan->save();
        $this->status_baru = $status;
        $this->pengaduan->refresh();

        $this->catatLogSensitif('update_status', $this->pengaduan, [
            'ticket_code' => $this->ticket_code,
            'status_baru' => $status,
        ]);

        session()->flash('success_status', 'Status berhasil diperbarui menjadi ' . strtoupper($status) . '!');
    }

    public function updatedStatusBaru()
    {
        if ($this->status_baru !== $this->pengaduan->status) {
            $this->ubahStatus($this->status_baru);
        }
    }

    public function bukaIdentitasDarurat(EnkripsiIdentitasService $enkripsiService)
    {
        // Hanya admin yang boleh melakukan ini
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Hanya Admin yang dapat membuka identitas anonim.');
        }

        if ($this->pengaduan->mode_privasi === 'anonim') {
            $this->identitasPelapor = $enkripsiService->bukaIdentitas($this->pengaduan);
            
            // Catat ke log bahwa admin telah membuka identitas ini
            $this->catatLogSensitif('buka_identitas_darurat', $this->pengaduan, [
                'ticket_code' => $this->ticket_code,
                'alasan'      => 'Dibuka manual oleh Admin',
            ]);
            
            session()->flash('success_identitas', 'Identitas asli pelapor berhasil dibuka untuk keperluan darurat.');
        }
    }

    public function suspendPelapor()
    {
        // Hanya admin yang boleh melakukan ini
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Hanya Admin yang dapat memblokir pengguna.');
        }

        if ($this->identitasPelapor && isset($this->identitasPelapor['user_id'])) {
            $userId = $this->identitasPelapor['user_id'];
            User::where('id', $userId)->update(['is_active' => false]);
            
            $this->catatLogSensitif('suspend_pelapor', $this->pengaduan, [
                'ticket_code' => $this->ticket_code,
                'user_id_suspended' => $userId,
            ]);

            session()->flash('success_suspend', 'Akun pelapor berhasil diblokir. Pelapor tidak akan bisa login lagi.');
        }
    }

    public function balasTanggapan()
    {
        $this->validate([
            'isi_tanggapan' => 'required|string|min:5',
        ]);

        TanggapanPengaduan::create([
            'pengaduan_id' => $this->pengaduan->id,
            'user_id' => Auth::id(),
            'isi_tanggapan' => $this->isi_tanggapan,
            'is_internal' => false,
        ]);

        $this->catatLogSensitif('balas_tanggapan', $this->pengaduan, [
            'ticket_code' => $this->ticket_code,
            'oleh' => Auth::user()->nama,
        ]);

        $this->isi_tanggapan = '';
        $this->pengaduan->refresh();
        session()->flash('success_tanggapan', 'Tanggapan berhasil dikirim!');
    }

    public function render()
    {
        return view('livewire.staff-dewan.detail-pengaduan');
    }
}
