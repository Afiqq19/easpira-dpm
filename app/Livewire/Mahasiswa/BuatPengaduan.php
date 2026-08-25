<?php

namespace App\Livewire\Mahasiswa;

use App\Models\KategoriPengaduan;
use App\Models\Pengaduan;
use App\Services\EnkripsiIdentitasService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

#[Layout('layouts.app')]
class BuatPengaduan extends Component
{
    use WithFileUploads;

    public $kategori_id;
    public $kategori_lainnya = '';
    public $isi;
    public bool $is_anonim = false; // true = anonim, false = umum
    
    // Properti untuk upload foto
    public $fotos = [];

    public function render()
    {
        return view('livewire.mahasiswa.buat-pengaduan', [
            'kategoriList' => KategoriPengaduan::all(),
        ]);
    }

    public function submit(EnkripsiIdentitasService $enkripsiService)
    {
        $this->validate([
            'kategori_id'      => 'required|exists:kategori_pengaduan,id',
            'kategori_lainnya' => 'nullable|string|max:100',
            'isi'              => 'required|string|min:20',
            'fotos'            => 'nullable|array|max:3',
            'fotos.*'          => 'max:10240', // Max 10MB per file (mendukung berbagai format foto termasuk HEIC/iPhone)
        ], [
            'isi.min'          => 'Isi pengaduan minimal 20 karakter untuk kejelasan.',
            'fotos.max'        => 'Maksimal hanya boleh mengunggah 3 foto.',
            'fotos.*.max'      => 'Ukuran setiap foto maksimal 10MB.',
        ]);

        $kategori = KategoriPengaduan::find($this->kategori_id);
        
        // Jika kategori Lainnya, tambahkan keterangan ke isi
        if ($kategori && strtolower($kategori->nama_kategori) === 'lainnya' && !empty($this->kategori_lainnya)) {
            $this->isi = "[Kategori: " . $this->kategori_lainnya . "]\n" . $this->isi;
        }
        
        $penanganan_khusus = $kategori->level_sensitivitas === 'tinggi' ? 1 : 0;
        
        // Tentukan mode_privasi dari boolean
        $mode_privasi = ($penanganan_khusus || $this->is_anonim) ? 'anonim' : 'umum';

        // Generate Ticket Code (Format: PLP-2026-RANDOM)
        $ticketCode = 'PLP-' . date('Y') . '-' . strtoupper(substr(uniqid(), -4));

        // Proses Upload & Kompresi Foto
        $lampiranPaths = [];
        if (!empty($this->fotos)) {
            $manager = new ImageManager(new Driver());
            
            foreach ($this->fotos as $foto) {
                $ext = strtolower($foto->getClientOriginalExtension() ?: 'jpg');
                $filename = uniqid('lampiran_') . '.' . $ext;
                $storageDir = 'public/lampiran/' . $ticketCode;
                $fullDir = storage_path('app/' . $storageDir);
                
                // Pastikan direktori ada
                if (!file_exists($fullDir)) {
                    mkdir($fullDir, 0755, true);
                }

                $saved = false;
                // Coba kompresi jika format gambar standar
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                    try {
                        $jpgName = uniqid('lampiran_') . '.jpg';
                        $fullPath = $fullDir . '/' . $jpgName;
                        $image = $manager->read($foto->getRealPath());
                        $image->scaleDown(width: 1280); // Kecilkan resolusi
                        $image->toJpeg(80)->save($fullPath);
                        $lampiranPaths[] = 'lampiran/' . $ticketCode . '/' . $jpgName;
                        $saved = true;
                    } catch (\Throwable $e) {
                        $saved = false;
                    }
                }

                // Fallback simpan file aslinya (misal HEIC, PDF, dll)
                if (!$saved) {
                    $stored = $foto->storeAs($storageDir, $filename);
                    $lampiranPaths[] = str_replace('public/', '', $stored);
                }
            }
        }

        $pengaduan = new Pengaduan();
        $pengaduan->ticket_code = $ticketCode;
        $pengaduan->kategori_id = $this->kategori_id;
        $pengaduan->isi = $this->isi;
        $pengaduan->mode_privasi = $mode_privasi;
        $pengaduan->status = 'diterima';
        $pengaduan->penanganan_khusus = $penanganan_khusus;
        
        // Simpan lampiran sebagai JSON jika ada
        if (!empty($lampiranPaths)) {
            $pengaduan->lampiran = $lampiranPaths;
        }

        if ($mode_privasi === 'anonim') {
            $pengaduan->user_id = null;
            $pengaduan->save();
            
            $enkripsiService->simpanIdentitas($pengaduan, [
                'user_id' => Auth::id(),
                'nama'    => Auth::user()->nama,
                'nim'     => Auth::user()->nim,
                'email'   => Auth::user()->email,
            ]);
        } else {
            $pengaduan->user_id = Auth::id();
            $pengaduan->save();
        }

        session()->flash('success', 'Pengaduan berhasil dikirim! Kode Tiket pelacakan Anda: ' . $ticketCode);
        return redirect()->route('mahasiswa.pengaduan.index');
    }
}
