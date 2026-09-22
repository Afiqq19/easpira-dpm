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
    public bool $is_anonim = false;
    
    public $fotos = [];

    public function render()
    {
        $kategoriList = KategoriPengaduan::all();
        if ($kategoriList->isEmpty()) {
            if (class_exists(\Database\Seeders\KategoriPengaduanSeeder::class)) {
                (new \Database\Seeders\KategoriPengaduanSeeder())->run();
                $kategoriList = KategoriPengaduan::all();
            }
        }

        return view('livewire.mahasiswa.buat-pengaduan', [
            'kategoriList' => $kategoriList,
        ]);
    }

    public function submit(EnkripsiIdentitasService $enkripsiService)
    {
        $this->validate([
            'kategori_id'      => 'required|exists:kategori_pengaduan,id',
            'kategori_lainnya' => 'nullable|string|max:100',
            'isi'              => 'required|string|min:20',
            'fotos'            => 'nullable|array|max:3',
            'fotos.*'          => 'max:15360',
        ], [
            'isi.min'          => 'Isi pengaduan minimal 20 karakter untuk kejelasan.',
            'fotos.max'        => 'Maksimal hanya boleh mengunggah 3 foto.',
            'fotos.*.max'      => 'Ukuran setiap foto maksimal 15MB.',
        ]);

        $kategori = KategoriPengaduan::find($this->kategori_id);
        
        if ($kategori && strtolower($kategori->nama_kategori) === 'lainnya' && !empty($this->kategori_lainnya)) {
            $this->isi = "[Kategori: " . $this->kategori_lainnya . "]\n" . $this->isi;
        }
        
        $namaKatLower = $kategori ? strtolower($kategori->nama_kategori) : '';
        $isSensitif = $kategori && (
            in_array($kategori->level_sensitivitas, ['sensitif', 'tinggi'])
            || str_contains($namaKatLower, 'pelecehan')
            || str_contains($namaKatLower, 'kekerasan')
        );

        $penanganan_khusus = $isSensitif ? 1 : 0;
        $mode_privasi = ($penanganan_khusus || $this->is_anonim) ? 'anonim' : 'umum';

        $ticketCode = 'PLP-' . date('Y') . '-' . strtoupper(substr(uniqid(), -4));

        $lampiranPaths = [];
        if (!empty($this->fotos)) {
            $manager = new ImageManager(new Driver());
            $publicDir = storage_path('app/public/lampiran/' . $ticketCode);
            if (!file_exists($publicDir)) {
                mkdir($publicDir, 0755, true);
            }
            
            foreach ($this->fotos as $foto) {
                $ext = strtolower($foto->getClientOriginalExtension() ?: 'jpg');
                $saved = false;

                try {
                    $jpgName = uniqid('lampiran_') . '.jpg';
                    $fullPath = $publicDir . '/' . $jpgName;
                    $image = $manager->read($foto->getRealPath());
                    $image->scaleDown(width: 1600);
                    $image->toJpeg(85)->save($fullPath);
                    $lampiranPaths[] = 'lampiran/' . $ticketCode . '/' . $jpgName;
                    $saved = true;
                } catch (\Throwable $e) {
                    $saved = false;
                }

                if (!$saved) {
                    $filename = uniqid('lampiran_') . '.' . $ext;
                    $stored = $foto->storeAs('lampiran/' . $ticketCode, $filename, 'public');
                    $lampiranPaths[] = $stored;
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
        
        if (!empty($lampiranPaths)) {
            $pengaduan->lampiran = $lampiranPaths;
        }

        $pengaduan->user_id = Auth::id(); // Selalu catat pemilik tiket agar masuk ke riwayat pengaduan mahasiswa

        if ($mode_privasi === 'anonim') {
            $pengaduan->kode_anonim = Pengaduan::generateKodeAnonim();
            $pengaduan->save();
            
            $enkripsiService->simpanIdentitas($pengaduan, [
                'user_id' => Auth::id(),
                'nama'    => Auth::user()->nama ?? Auth::user()->name,
                'nim'     => Auth::user()->nim,
                'email'   => Auth::user()->email,
            ]);
        } else {
            $pengaduan->save();
        }

        session()->flash('success', 'Pengaduan berhasil dikirim! Kode Tiket pelacakan Anda: ' . $ticketCode);
        return redirect()->route('mahasiswa.pengaduan.index');
    }
}
