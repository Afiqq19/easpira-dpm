<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
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

        $filename = 'Data_Pengaduan_e-Aspira_' . now()->format('d-m-Y') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $response = new StreamedResponse(function () use ($pengaduans) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM supaya Excel bisa membaca karakter Indonesia dengan benar
            fwrite($handle, "\xEF\xBB\xBF");

            // Header kolom
            fputcsv($handle, [
                'No',
                'Kode Tiket',
                'Nama Pelapor',
                'Email Pelapor',
                'Kategori',
                'Isi Pengaduan',
                'Mode Privasi',
                'Status',
                'Tanggal Masuk',
                'Jam Masuk',
                'Tanggal Selesai',
                'Jam Selesai',
            ], ';');

            $no = 1;
            foreach ($pengaduans as $pengaduan) {
                // Jika anonim, sembunyikan nama & email
                if ($pengaduan->mode_privasi === 'anonim') {
                    $nama = 'Anonim';
                    $email = 'Anonim';
                } else {
                    $nama = $pengaduan->user->name ?? 'User Terhapus';
                    $email = $pengaduan->user->email ?? '-';
                }

                // Tanggal selesai: ambil dari updated_at jika status = selesai
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

                fputcsv($handle, [
                    $no++,
                    $pengaduan->ticket_code,
                    $nama,
                    $email,
                    $pengaduan->kategori->nama_kategori ?? 'Umum',
                    str_replace(["\r\n", "\n", "\r"], ' ', strip_tags($pengaduan->isi)),
                    ucfirst($pengaduan->mode_privasi),
                    ucfirst($pengaduan->status),
                    $pengaduan->created_at->format('d/m/Y'),
                    $pengaduan->created_at->format('H:i'),
                    $tanggalSelesai,
                    $jamSelesai,
                ], ';');
            }

            fclose($handle);
        }, 200, $headers);

        return $response;
    }
}
