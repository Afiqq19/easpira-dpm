<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Services\SimpleXlsxExporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportPengaduanController extends Controller
{
    public function export(Request $request)
    {
        $user = Auth::user();

        // Hanya admin, staff_dewan, direktur, wakil_direktur yang boleh export
        if (!$user->hasAnyRole(['admin', 'staff_dewan', 'direktur', 'wakil_direktur'])) {
            abort(403, 'Anda tidak memiliki izin untuk mengekspor data.');
        }

        $pengaduans = Pengaduan::with(['kategori', 'user'])
            ->latest()
            ->get();

        // Setup Headers (BOLD & CAPITALIZED)
        $headers = [
            'NO', 
            'KODE TIKET', 
            'NAMA PELAPOR', 
            'EMAIL PELAPOR', 
            'KATEGORI', 
            'ISI PENGADUAN', 
            'MODE PRIVASI', 
            'STATUS', 
            'TANGGAL MASUK', 
            'JAM MASUK', 
            'TANGGAL SELESAI', 
            'JAM SELESAI'
        ];

        // Column widths for optimal display in Excel
        $colWidths = [6, 18, 24, 26, 20, 45, 16, 16, 16, 13, 16, 13];

        $rows = [];
        $no = 1;

        foreach ($pengaduans as $pengaduan) {
            if ($pengaduan->mode_privasi === 'anonim') {
                $nama = 'Anonim';
                $email = 'Anonim';
            } else {
                $nama = $pengaduan->user->name ?? 'User Terhapus';
                $email = $pengaduan->user->email ?? '-';
            }

            $tanggalSelesai = '-';
            $jamSelesai = '-';
            if ($pengaduan->status === 'selesai') {
                if ($pengaduan->ditangani_pada) {
                    $tanggalSelesai = $pengaduan->ditangani_pada->format('d/m/Y');
                    $jamSelesai = $pengaduan->ditangani_pada->format('H:i');
                } else {
                    $tanggalSelesai = $pengaduan->updated_at->format('d/m/Y');
                    $jamSelesai = $pengaduan->updated_at->format('H:i');
                }
            }

            $cleanIsi = str_replace(["\r\n", "\n", "\r", "\t"], ' ', strip_tags($pengaduan->isi));
            $cleanIsi = preg_replace('/\s+/', ' ', trim($cleanIsi));

            $rows[] = [
                $no++,
                $pengaduan->ticket_code,
                $nama,
                $email,
                $pengaduan->kategori->nama_kategori ?? 'Umum',
                $cleanIsi,
                ucfirst($pengaduan->mode_privasi ?? 'terbuka'),
                ucfirst($pengaduan->status ?? 'menunggu'),
                $pengaduan->created_at ? $pengaduan->created_at->format('d/m/Y') : '-',
                $pengaduan->created_at ? $pengaduan->created_at->format('H:i') : '-',
                $tanggalSelesai,
                $jamSelesai
            ];
        }

        $mainTitle = 'LAPORAN DATA PENGADUAN MAHASISWA e-ASPIRA DPM POLMED';
        $subTitle = 'Diekspor pada: ' . now()->translatedFormat('d F Y, H:i') . ' WIB | Oleh: ' . ($user->name ?? $user->username);

        $xlsxContent = SimpleXlsxExporter::create(
            'Data Pengaduan',
            $headers,
            $rows,
            $colWidths,
            $mainTitle,
            $subTitle
        );

        $filename = 'Data_Pengaduan_e-Aspira_' . now()->format('d-m-Y_His') . '.xlsx';

        return response($xlsxContent, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'max-age=0, no-cache, no-store, must-revalidate',
            'Pragma'              => 'public',
        ]);
    }
}
