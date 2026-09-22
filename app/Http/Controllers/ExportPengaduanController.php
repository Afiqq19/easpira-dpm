<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Pengaduan');

        // Setup Headers (BOLD & CAPITALIZED)
        $headers = [
            'NO', 'KODE TIKET', 'NAMA PELAPOR', 'EMAIL PELAPOR', 'KATEGORI', 
            'ISI PENGADUAN', 'MODE PRIVASI', 'STATUS', 'TANGGAL MASUK', 
            'JAM MASUK', 'TANGGAL SELESAI', 'JAM SELESAI'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            
            // Format Bold & Background Color
            $styleArray = [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFFFF'], // White text
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF4F46E5'], // Indigo-600 background
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ]
            ];
            $sheet->getStyle($col . '1')->applyFromArray($styleArray);
            $col++;
        }

        $row = 2;
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

            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $pengaduan->ticket_code);
            $sheet->setCellValue('C' . $row, $nama);
            $sheet->setCellValue('D' . $row, $email);
            $sheet->setCellValue('E' . $row, $pengaduan->kategori->nama_kategori ?? 'Umum');
            $sheet->setCellValue('F' . $row, str_replace(["\r\n", "\n", "\r"], ' ', strip_tags($pengaduan->isi)));
            $sheet->setCellValue('G' . $row, ucfirst($pengaduan->mode_privasi));
            $sheet->setCellValue('H' . $row, ucfirst($pengaduan->status));
            
            // Setting format as string using setCellValueExplicit to avoid ##### formatting issues in Excel
            $sheet->setCellValueExplicit('I' . $row, $pengaduan->created_at->format('d/m/Y'), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('J' . $row, $pengaduan->created_at->format('H:i'), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('K' . $row, $tanggalSelesai, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('L' . $row, $jamSelesai, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'L') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $filename = 'Data_Pengaduan_e-Aspira_' . now()->format('d-m-Y') . '.xlsx';

        $response = new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
}
