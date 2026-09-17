<?php

namespace App\Services;

use App\Models\Pengunjung;
use Carbon\Carbon;

class PengunjungSpreadsheetService
{
    /**
     * Generate format file Excel (.xls berbasis HTML Table)
     * Format ini kompatibel dengan Microsoft Excel, WPS Office, dan LibreOffice.
     */
    public function generateExcelHtml(array $headers, array $rows, string $title = 'Buku Tamu & Kunjungan Perpustakaan'): string
    {
        $html = '<!DOCTYPE html>' . "\n";
        $html .= '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">' . "\n";
        $html .= '<head>' . "\n";
        $html .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />' . "\n";
        $html .= '<title>' . htmlspecialchars($title) . '</title>' . "\n";
        $html .= '<style>' . "\n";
        $html .= 'body { font-family: "Segoe UI", Arial, sans-serif; font-size: 11pt; color: #1e293b; }' . "\n";
        $html .= 'table { border-collapse: collapse; width: 100%; margin-top: 10px; }' . "\n";
        $html .= 'th { background-color: #0f766e; color: #ffffff; font-weight: bold; text-align: left; padding: 10px 8px; border: 1px solid #0d5f58; white-space: nowrap; }' . "\n";
        $html .= 'td { padding: 8px; border: 1px solid #cbd5e1; vertical-align: top; mso-number-format: "\@"; }' . "\n";
        $html .= '.num { text-align: center; }' . "\n";
        $html .= 'tr:nth-child(even) { background-color: #f8fafc; }' . "\n";
        $html .= '.title-header { font-size: 15pt; font-weight: bold; color: #0f766e; margin-bottom: 4px; }' . "\n";
        $html .= '.sub-header { font-size: 10pt; color: #64748b; margin-bottom: 12px; }' . "\n";
        $html .= '</style>' . "\n";
        $html .= '</head>' . "\n";
        $html .= '<body>' . "\n";
        $html .= '<div class="title-header">' . htmlspecialchars($title) . ' - Perpustakaan SMK Negeri 1 Tirtamulya</div>' . "\n";
        $html .= '<div class="sub-header">Diekspor pada: ' . date('d F Y H:i:s') . ' | Total Data: ' . count($rows) . ' Orang</div>' . "\n";
        $html .= '<table>' . "\n";
        $html .= '<thead><tr>' . "\n";

        foreach ($headers as $header) {
            $html .= '<th>' . htmlspecialchars($header) . '</th>' . "\n";
        }
        $html .= '</tr></thead>' . "\n";
        $html .= '<tbody>' . "\n";

        foreach ($rows as $row) {
            $html .= '<tr>' . "\n";
            foreach ($row as $key => $cell) {
                $alignClass = ($key === 'no' || $key === 'tanggal_kunjungan' || $key === 'waktu_masuk') ? ' class="num"' : '';
                $html .= '<td' . $alignClass . '>' . htmlspecialchars((string)$cell) . '</td>' . "\n";
            }
            $html .= '</tr>' . "\n";
        }

        $html .= '</tbody>' . "\n";
        $html .= '</table>' . "\n";
        $html .= '</body></html>';

        return $html;
    }

    /**
     * Generate file CSV dengan UTF-8 BOM untuk kompatibilitas sempurna
     */
    public function generateCsv(array $headers, array $rows, string $delimiter = ';'): string
    {
        $output = fopen('php://temp', 'r+');
        // UTF-8 BOM
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // Tulis header
        fputcsv($output, array_values($headers), $delimiter);

        // Tulis baris data
        foreach ($rows as $row) {
            fputcsv($output, array_values($row), $delimiter);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv;
    }

    /**
     * Export data pengunjung ke Excel (.xls) atau CSV (.csv)
     */
    public function exportPengunjung($visitors, string $format = 'xls'): array
    {
        $headers = [
            'no'                => 'No',
            'nama'              => 'Nama Pengunjung',
            'tipe'              => 'Tipe (Guru / Siswa)',
            'kelas_jabatan'     => 'Kelas / Jabatan',
            'nis_nip'           => 'NIS / NIP',
            'tanggal_kunjungan' => 'Tanggal Kunjungan',
            'waktu_masuk'       => 'Waktu Masuk',
            'keperluan'         => 'Keperluan Kunjungan',
        ];

        $rows = [];
        $no = 1;
        foreach ($visitors as $v) {
            $cleanNama = $v->nama;
            if (!empty($v->nis_nip)) {
                $rawNis = preg_replace('/[^0-9]/', '', $v->nis_nip);
                if (!empty($rawNis)) {
                    $cleanNama = preg_replace('/\s*' . preg_quote($rawNis, '/') . '$/', '', $cleanNama);
                }
                $cleanNama = preg_replace('/\s*' . preg_quote($v->nis_nip, '/') . '$/i', '', $cleanNama);
            }

            $rows[] = [
                'no'                => $no++,
                'nama'              => trim($cleanNama),
                'tipe'              => ucfirst($v->tipe),
                'kelas_jabatan'     => $v->kelas_jabatan ?? '-',
                'nis_nip'           => $v->nis_nip ?? '-',
                'tanggal_kunjungan' => $v->tanggal_kunjungan ? Carbon::parse($v->tanggal_kunjungan)->format('d/m/Y') : '-',
                'waktu_masuk'       => $v->waktu_masuk ? Carbon::parse($v->waktu_masuk)->format('H:i') . ' WIB' : '-',
                'keperluan'         => $v->keperluan ?? 'Berkunjung ke perpustakaan',
            ];
        }

        $dateSuffix = date('Ymd_His');

        if ($format === 'csv') {
            return [
                'content'  => $this->generateCsv($headers, $rows),
                'filename' => "Data_Pengunjung_SMK_{$dateSuffix}.csv",
                'mime'     => 'text/csv; charset=UTF-8',
            ];
        }

        return [
            'content'  => $this->generateExcelHtml($headers, $rows, 'Data Kunjungan Pengunjung'),
            'filename' => "Data_Pengunjung_SMK_{$dateSuffix}.xls",
            'mime'     => 'application/vnd.ms-excel; charset=UTF-8',
        ];
    }
}
