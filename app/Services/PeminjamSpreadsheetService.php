<?php

namespace App\Services;

use App\Models\Peminjam;

class PeminjamSpreadsheetService
{
    /**
     * Generate format file Excel (.xls berbasis HTML Table)
     * Format ini kompatibel dengan Microsoft Excel, WPS Office, dan LibreOffice.
     */
    public function generateExcelHtml(array $headers, array $rows, string $title = 'Data Anggota & Peminjam Perpustakaan'): string
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
        $html .= '.int-val { text-align: right; mso-number-format: "0"; }' . "\n";
        $html .= 'tr:nth-child(even) { background-color: #f8fafc; }' . "\n";
        $html .= '.title-header { font-size: 15pt; font-weight: bold; color: #0f766e; margin-bottom: 4px; }' . "\n";
        $html .= '.sub-header { font-size: 10pt; color: #64748b; margin-bottom: 12px; }' . "\n";
        $html .= '</style>' . "\n";
        $html .= '</head>' . "\n";
        $html .= '<body>' . "\n";
        $html .= '<div class="title-header">' . htmlspecialchars($title) . ' - Perpustakaan SMK Negeri 1 Tirtamulya</div>' . "\n";
        $html .= '<div class="sub-header">Diekspor pada: ' . date('d F Y H:i:s') . ' | Total Data: ' . count($rows) . ' Anggota</div>' . "\n";
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
                $alignClass = ($key === 'no' || $key === 'tipe') ? ' class="num"' : (($key === 'buku_dipinjam') ? ' class="int-val"' : '');
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
     * Generate file CSV dengan UTF-8 BOM
     */
    public function generateCsv(array $headers, array $rows, string $delimiter = ';'): string
    {
        $output = fopen('php://temp', 'r+');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($output, array_values($headers), $delimiter);

        foreach ($rows as $row) {
            fputcsv($output, array_values($row), $delimiter);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv;
    }

    /**
     * Export data peminjam ke Excel (.xls) atau CSV (.csv)
     */
    public function exportPeminjam($peminjamList, string $format = 'xls'): array
    {
        $headers = [
            'no'             => 'No',
            'nama'           => 'Nama Peminjam',
            'tipe'           => 'Tipe (Guru / Siswa)',
            'kelas_jabatan'  => 'Kelas / Jabatan',
            'nis_nip'        => 'NIS / NIP',
            'telepon'        => 'No. Telepon / WA',
            'buku_dipinjam'  => 'Buku Dipinjam (Aktif)',
            'keterangan'     => 'Status Pengembalian',
            'alamat'         => 'Alamat',
        ];

        $rows = [];
        $no = 1;
        foreach ($peminjamList as $p) {
            $aktif = $p->buku_dipinjam ?? 0;
            $total = $p->total_pinjam ?? 0;
            if ($total === 0 || $aktif === 0) {
                $ket = 'SELESAI';
            } elseif ($aktif === $total) {
                $ket = 'BELUM KEMBALI';
            } else {
                $ket = 'SEBAGIAN KEMBALI';
            }

            $cleanNama = $p->nama;
            if (!empty($p->nis_nip)) {
                $rawNis = preg_replace('/[^0-9]/', '', $p->nis_nip);
                if (!empty($rawNis)) {
                    $cleanNama = preg_replace('/\s*' . preg_quote($rawNis, '/') . '$/', '', $cleanNama);
                }
                $cleanNama = preg_replace('/\s*' . preg_quote($p->nis_nip, '/') . '$/i', '', $cleanNama);
            }

            $rows[] = [
                'no'            => $no++,
                'nama'          => trim($cleanNama),
                'tipe'          => ucfirst($p->tipe),
                'kelas_jabatan' => $p->kelas_jabatan ?? '-',
                'nis_nip'       => $p->nis_nip ?? '-',
                'telepon'       => $p->telepon ?? '-',
                'buku_dipinjam' => $aktif . ' Buku',
                'keterangan'    => $ket,
                'alamat'        => $p->alamat ?? '-',
            ];
        }

        $dateSuffix = date('Ymd_His');

        if ($format === 'csv') {
            return [
                'content'  => $this->generateCsv($headers, $rows),
                'filename' => "Data_Peminjam_SMK_{$dateSuffix}.csv",
                'mime'     => 'text/csv; charset=UTF-8',
            ];
        }

        return [
            'content'  => $this->generateExcelHtml($headers, $rows, 'Data Anggota & Peminjam Buku'),
            'filename' => "Data_Peminjam_SMK_{$dateSuffix}.xls",
            'mime'     => 'application/vnd.ms-excel; charset=UTF-8',
        ];
    }
}
