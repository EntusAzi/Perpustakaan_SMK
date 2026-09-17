<?php

namespace App\Services;

class LaporanSpreadsheetService
{
    /**
     * Generate format file Excel (.xls berbasis HTML Table)
     * Sesuai dengan format baku laporan fisik perpustakaan (Sirkulasi & Evaluasi).
     */
    public function generateExcelHtml(array $laporanBulanan, string $startDate, string $endDate): string
    {
        $html = '<!DOCTYPE html>' . "\n";
        $html .= '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">' . "\n";
        $html .= '<head>' . "\n";
        $html .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />' . "\n";
        $html .= '<title>Laporan Rekapitulasi Sirkulasi & Evaluasi Perpustakaan</title>' . "\n";
        $html .= '<style>' . "\n";
        $html .= 'body { font-family: "Segoe UI", Arial, sans-serif; font-size: 11pt; color: #1e293b; }' . "\n";
        $html .= 'table { border-collapse: collapse; width: 100%; margin-top: 8px; margin-bottom: 12px; }' . "\n";
        $html .= 'th { background-color: #0f766e; color: #ffffff; font-weight: bold; text-align: center; padding: 8px; border: 1px solid #0d5f58; }' . "\n";
        $html .= 'td { padding: 6px 8px; border: 1px solid #cbd5e1; vertical-align: middle; }' . "\n";
        $html .= '.center { text-align: center; }' . "\n";
        $html .= '.bold { font-weight: bold; }' . "\n";
        $html .= '.month-header { font-size: 13pt; font-weight: bold; color: #0f172a; margin-top: 20px; margin-bottom: 6px; text-transform: uppercase; }' . "\n";
        $html .= '.sec-title { font-weight: bold; font-size: 11pt; margin-top: 10px; margin-bottom: 2px; }' . "\n";
        $html .= '</style>' . "\n";
        $html .= '</head>' . "\n";
        $html .= '<body>' . "\n";
        $html .= '<div style="font-size: 15pt; font-weight: bold; color: #0f766e;">LAPORAN REKAPITULASI SIRKULASI & EVALUASI PERPUSTAKAAN</div>' . "\n";
        $html .= '<div style="font-size: 11pt; color: #64748b; margin-bottom: 16px;">SMK Negeri 1 Tirtamulya | Periode: ' . htmlspecialchars($startDate) . ' s/d ' . htmlspecialchars($endDate) . '</div>' . "\n";

        foreach ($laporanBulanan as $item) {
            $html .= '<div class="month-header">' . htmlspecialchars($item['bulan_label']) . '</div>' . "\n";
            $html .= '<table>' . "\n";
            $html .= '<thead>' . "\n";
            $html .= '<tr>' . "\n";
            $html .= '<th rowspan="2" style="width: 45px;">No</th>' . "\n";
            $html .= '<th rowspan="2" style="width: 200px; text-align: left; padding-left: 10px;">Jenis Anggota</th>' . "\n";
            $html .= '<th colspan="2">Jumlah Pengunjung</th>' . "\n";
            $html .= '<th colspan="2">Jumlah Peminjam</th>' . "\n";
            $html .= '</tr>' . "\n";
            $html .= '<tr>' . "\n";
            $html .= '<th style="width: 90px;">Online</th>' . "\n";
            $html .= '<th style="width: 90px;">Offline</th>' . "\n";
            $html .= '<th style="width: 90px;">Online</th>' . "\n";
            $html .= '<th style="width: 90px;">Offline</th>' . "\n";
            $html .= '</tr>' . "\n";
            $html .= '</thead>' . "\n";
            $html .= '<tbody>' . "\n";

            $rows = [
                ['1', 'Siswa', '-', $item['pengunjung']['siswa'] ?: '-', '-', $item['peminjam']['siswa'] ?: '-'],
                ['2', 'Guru/karyawan', '-', $item['pengunjung']['guru'] ?: '-', '-', $item['peminjam']['guru'] ?: '-'],
                ['3', 'Kepsek', '-', '-', '-', '-'],
                ['4', 'Masyarakat', '-', '-', '-', '-'],
            ];

            foreach ($rows as $r) {
                $html .= '<tr>' . "\n";
                $html .= '<td class="center">' . $r[0] . '</td>' . "\n";
                $html .= '<td style="padding-left: 10px;">' . $r[1] . '</td>' . "\n";
                $html .= '<td class="center">' . $r[2] . '</td>' . "\n";
                $html .= '<td class="center">' . $r[3] . '</td>' . "\n";
                $html .= '<td class="center">' . $r[4] . '</td>' . "\n";
                $html .= '<td class="center">' . $r[5] . '</td>' . "\n";
                $html .= '</tr>' . "\n";
            }

            // Baris Jumlah
            $html .= '<tr class="bold" style="background-color: #f1f5f9;">' . "\n";
            $html .= '<td colspan="2" class="center">Jumlah</td>' . "\n";
            $html .= '<td class="center">-</td>' . "\n";
            $html .= '<td class="center">' . ($item['pengunjung']['total'] ?: 0) . '</td>' . "\n";
            $html .= '<td class="center">-</td>' . "\n";
            $html .= '<td class="center">' . ($item['peminjam']['total'] ?: 0) . '</td>' . "\n";
            $html .= '</tr>' . "\n";

            $html .= '</tbody>' . "\n";
            $html .= '</table>' . "\n";

            // Sirkulasi
            $html .= '<div class="sec-title">SIRKULASI</div>' . "\n";
            $html .= '<div style="margin-bottom: 8px;">Jumlah buku terpinjam &nbsp;&nbsp;: <strong>' . $item['sirkulasi']['buku_terpinjam'] . ' eksemplar</strong></div>' . "\n";

            // Evaluasi
            $html .= '<div class="sec-title">EVALUASI</div>' . "\n";
            $html .= '<table style="width: auto; border: none; margin-top: 2px;">' . "\n";
            $html .= '<tr><td style="border:none; border-bottom: 1px solid #000000; padding: 2px 14px 2px 0;">Rata-rata pengunjung perhari</td><td style="border:none; border-bottom: 1px solid #000000; padding: 2px;">: ' . ($item['evaluasi']['rata_pengunjung'] ?: '-') . ' orang</td></tr>' . "\n";
            $html .= '<tr><td style="border:none; padding: 2px 14px 2px 0;">Rata-rata peminjam perhari</td><td style="border:none; padding: 2px;">: ' . ($item['evaluasi']['rata_peminjam'] ?: '-') . ' orang</td></tr>' . "\n";
            $html .= '<tr><td style="border:none; padding: 2px 14px 2px 0;">Rata-rata buku terpinjam</td><td style="border:none; padding: 2px;">: ' . ($item['evaluasi']['rata_buku'] ?: '-') . ' eksemplar/hari</td></tr>' . "\n";
            $html .= '</table>' . "\n";
            $html .= '<br>' . "\n";
        }

        $html .= '</body></html>';

        return $html;
    }

    public function exportLaporan(array $laporanBulanan, string $startDate, string $endDate): array
    {
        $dateSuffix = date('Ymd_His');

        return [
            'content'  => $this->generateExcelHtml($laporanBulanan, $startDate, $endDate),
            'filename' => "Laporan_Sirkulasi_Evaluasi_SMK_{$dateSuffix}.xls",
            'mime'     => 'application/vnd.ms-excel; charset=UTF-8',
        ];
    }
}
