<?php

namespace App\Services;

use App\Models\Buku;
use Illuminate\Support\Str;
use ZipArchive;
use SimpleXMLElement;

class BookSpreadsheetService
{
    /**
     * Definisi kolom template dan atribut buku
     */
    public const COLUMNS = [
        'id_buku'       => 'ID Buku',
        'no_inventaris' => 'No Inventaris',
        'judul'         => 'Judul Buku',
        'penulis'       => 'Pengarang',
        'penerbit'      => 'Penerbit',
        'tahun_terbit'  => 'Tahun Terbit',
        'stok'          => 'Jumlah Buku',
        'kelas'         => 'Kelas',
        'kurikulum'     => 'Kurikulum',
        'sumber'        => 'Sumber Buku',
        'keterangan'    => 'Keterangan Mapel',
        'tanggal_masuk' => 'Tanggal Masuk',
        'nomor_rak'     => 'Nomor Rak',
        'kategori'      => 'Kategori',
        'isbn'          => 'ISBN',
        'deskripsi'     => 'Deskripsi / Sinopsis',
    ];

    /**
     * Contoh data untuk template download
     */
    public const SAMPLE_ROWS = [
        [
            'id_buku'       => 'BK-0001',
            'no_inventaris' => 'INV/2026/001',
            'judul'         => 'Matematika Tingkat Lanjut Kelas XI',
            'penulis'       => 'Budi Raharjo, M.Pd.',
            'penerbit'      => 'Erlangga',
            'tahun_terbit'  => '2023',
            'stok'          => '30',
            'kelas'         => 'XI',
            'kurikulum'     => 'Kurikulum Merdeka',
            'sumber'        => 'BOS 2023',
            'keterangan'    => 'Matematika',
            'tanggal_masuk' => '2024-01-15',
            'nomor_rak'     => 'RAK-B2',
            'kategori'      => 'lks_paket',
            'isbn'          => '978-602-01-1234-5',
            'deskripsi'     => 'Buku pegangan siswa kelas XI untuk peminatan MIPA dan Kejuruan Teknik.',
        ],
        [
            'id_buku'       => 'BK-0002',
            'no_inventaris' => 'INV/2026/002',
            'judul'         => 'Pemrograman Web Dasar dengan HTML, CSS, dan JavaScript',
            'penulis'       => 'Ahmad Zaki, S.Kom.',
            'penerbit'      => 'Informatika Bandung',
            'tahun_terbit'  => '2022',
            'stok'          => '20',
            'kelas'         => 'X',
            'kurikulum'     => 'Kurikulum Merdeka',
            'sumber'        => 'BOS 2024',
            'keterangan'    => 'Rekayasa Perangkat Lunak (RPL)',
            'tanggal_masuk' => '2024-02-10',
            'nomor_rak'     => 'RAK-C1',
            'kategori'      => 'referensi',
            'isbn'          => '978-602-87-4321-0',
            'deskripsi'     => 'Buku teks dasar pemrograman front-end untuk siswa SMK jurusan RPL dan TKJ.',
        ],
        [
            'id_buku'       => 'BK-0003',
            'no_inventaris' => 'INV/2026/003',
            'judul'         => 'Laskar Pelangi',
            'penulis'       => 'Andrea Hirata',
            'penerbit'      => 'Bentang Pustaka',
            'tahun_terbit'  => '2020',
            'stok'          => '10',
            'kelas'         => 'Semua',
            'kurikulum'     => 'Umum',
            'sumber'        => 'Hibah Alumni',
            'keterangan'    => 'Sastra Indonesia',
            'tanggal_masuk' => '2024-03-01',
            'nomor_rak'     => 'RAK-F1',
            'kategori'      => 'karya_fiksi',
            'isbn'          => '978-979-1227-78-0',
            'deskripsi'     => 'Novel inspiratif tentang perjuangan anak-anak Belitong dalam menuntut ilmu.',
        ],
    ];

    /**
     * Generate format file Excel (.xls berbasis XML Spreadsheet/HTML Table)
     * Format ini langsung dibuka oleh Microsoft Excel, WPS Office, dan LibreOffice
     * dengan styling rapi, warna header, dan border tanpa perlu library vendor berat.
     */
    public function generateExcelHtml(array $headers, array $rows, string $title = 'Data Buku'): string
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
        $html .= '<div class="sub-header">Diekspor pada: ' . date('d F Y H:i:s') . ' | Total Data: ' . count($rows) . ' Baris</div>' . "\n";
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
                $alignClass = '';
                if ($key === 'no' || $key === 'tahun_terbit' || $key === 'kelas') {
                    $alignClass = ' class="num"';
                } elseif ($key === 'stok' || $key === 'stok_tersedia') {
                    $alignClass = ' class="int-val"';
                }
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
     * Generate file CSV dengan UTF-8 BOM untuk kompatibilitas sempurna dengan Excel
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
     * Mengunduh template import buku (.xls / .csv)
     */
    public function getTemplateContent(string $format = 'xls'): array
    {
        $headers = array_values(self::COLUMNS);
        $rows = [];
        foreach (self::SAMPLE_ROWS as $sample) {
            $row = [];
            foreach (array_keys(self::COLUMNS) as $colKey) {
                $row[$colKey] = $sample[$colKey] ?? '';
            }
            $rows[] = $row;
        }

        if ($format === 'csv') {
            return [
                'content'  => $this->generateCsv($headers, $rows),
                'filename' => 'Template_Import_Buku_SMK.csv',
                'mime'     => 'text/csv; charset=UTF-8',
            ];
        }

        return [
            'content'  => $this->generateExcelHtml($headers, $rows, 'Template Import Data Koleksi Buku'),
            'filename' => 'Template_Import_Buku_SMK.xls',
            'mime'     => 'application/vnd.ms-excel; charset=UTF-8',
        ];
    }

    /**
     * Export data buku koleksi ke Excel (.xls) atau CSV (.csv)
     */
    public function exportBooks($books, string $format = 'xls'): array
    {
        $headers = [
            'no'            => 'No',
            'id_buku'       => 'ID Buku',
            'no_inventaris' => 'No. Inventaris',
            'judul'         => 'Judul Buku',
            'penulis'       => 'Pengarang / Penulis',
            'penerbit'      => 'Penerbit',
            'tahun_terbit'  => 'Tahun Terbit',
            'stok'          => 'Total Stok',
            'stok_tersedia' => 'Stok Tersedia',
            'kelas'         => 'Kelas',
            'kurikulum'     => 'Kurikulum',
            'sumber'        => 'Sumber Buku',
            'keterangan'    => 'Keterangan (Mapel)',
            'nomor_rak'     => 'Nomor Rak',
            'tanggal_masuk' => 'Tanggal Masuk',
            'kategori'      => 'Kategori',
            'isbn'          => 'ISBN',
            'deskripsi'     => 'Deskripsi',
        ];

        $rows = [];
        $no = 1;
        foreach ($books as $b) {
            $rows[] = [
                'no'            => $no++,
                'id_buku'       => $b->id_buku ?? ('BK-' . str_pad($b->id, 4, '0', STR_PAD_LEFT)),
                'no_inventaris' => $b->no_inventaris ?? '-',
                'judul'         => $b->judul ?? '-',
                'penulis'       => $b->penulis ?? '-',
                'penerbit'      => $b->penerbit ?? '-',
                'tahun_terbit'  => $b->tahun_terbit ?? '-',
                'stok'          => $b->stok ?? 0,
                'stok_tersedia' => $b->stok_tersedia ?? 0,
                'kelas'         => $b->kelas ? ('Kelas ' . $b->kelas) : 'Semua',
                'kurikulum'     => $b->kurikulum ?? '-',
                'sumber'        => $b->sumber ?? '-',
                'keterangan'    => $b->keterangan ?? '-',
                'nomor_rak'     => $b->nomor_rak ?? '-',
                'tanggal_masuk' => $b->tanggal_masuk ? $b->tanggal_masuk->format('Y-m-d') : '-',
                'kategori'      => $b->kategori_label ?? $b->kategori,
                'isbn'          => $b->isbn ?? '-',
                'deskripsi'     => $b->deskripsi ?? '-',
            ];
        }

        $dateSuffix = date('Ymd_His');

        if ($format === 'csv') {
            return [
                'content'  => $this->generateCsv($headers, $rows),
                'filename' => "Data_Koleksi_Buku_SMK_{$dateSuffix}.csv",
                'mime'     => 'text/csv; charset=UTF-8',
            ];
        }

        return [
            'content'  => $this->generateExcelHtml($headers, $rows, 'Daftar Koleksi Buku Perpustakaan SMK'),
            'filename' => "Data_Koleksi_Buku_SMK_{$dateSuffix}.xls",
            'mime'     => 'application/vnd.ms-excel; charset=UTF-8',
        ];
    }

    /**
     * Membaca berkas unggahan (.xlsx, .xls, .csv) menjadi array baris data
     */
    public function parseUploadedFile(string $filePath, string $extension): array
    {
        $extension = strtolower($extension);

        if ($extension === 'xlsx') {
            return $this->parseXlsx($filePath);
        }

        if ($extension === 'csv' || $extension === 'txt') {
            return $this->parseCsv($filePath);
        }

        if ($extension === 'xls') {
            // Cek apakah file .xls sebenarnya file HTML/XML atau CSV
            $sample = file_get_contents($filePath, false, null, 0, 500);
            if (stripos($sample, '<table') !== false || stripos($sample, '<html') !== false) {
                return $this->parseHtmlTable($filePath);
            }
            return $this->parseCsv($filePath);
        }

        throw new \Exception("Format file .{$extension} tidak didukung. Silakan gunakan .xlsx, .xls, atau .csv.");
    }

    /**
     * Parse file XLSX murni menggunakan ZipArchive & SimpleXML
     */
    protected function parseXlsx(string $filePath): array
    {
        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) {
            throw new \Exception('Tidak dapat membuka file Excel (.xlsx). Pastikan file tidak rusak atau terproteksi kata sandi.');
        }

        // 1. Baca shared strings jika ada
        $sharedStrings = [];
        if (($idx = $zip->locateName('xl/sharedStrings.xml')) !== false) {
            $xmlStr = $zip->getFromIndex($idx);
            if ($xmlStr) {
                $xml = simplexml_load_string($xmlStr);
                if ($xml && isset($xml->si)) {
                    foreach ($xml->si as $si) {
                        $str = '';
                        if (isset($si->t)) {
                            $str = (string)$si->t;
                        } elseif (isset($si->r)) {
                            foreach ($si->r as $r) {
                                $str .= (string)$r->t;
                            }
                        }
                        $sharedStrings[] = $str;
                    }
                }
            }
        }

        // 2. Baca sheet data (sheet1.xml atau sheet pertama yang ditemukan)
        $sheetXmlContent = null;
        for ($i = 1; $i <= 5; $i++) {
            $sheetName = "xl/worksheets/sheet{$i}.xml";
            if ($zip->locateName($sheetName) !== false) {
                $sheetXmlContent = $zip->getFromName($sheetName);
                break;
            }
        }

        if (!$sheetXmlContent) {
            $zip->close();
            throw new \Exception('Lembar kerja (worksheet) pada file Excel tidak ditemukan.');
        }

        $xml = simplexml_load_string($sheetXmlContent);
        $zip->close();

        if (!$xml || !isset($xml->sheetData->row)) {
            return [];
        }

        $rows = [];
        foreach ($xml->sheetData->row as $row) {
            $rowArr = [];
            foreach ($row->c as $c) {
                $cellRef = (string)$c['r']; // Misal: A1, B1, AC1
                preg_match('/([A-Z]+)(\d+)/', $cellRef, $m);
                $colLetters = $m[1] ?? 'A';

                // Hitung index 0-based
                $targetIdx = 0;
                $len = strlen($colLetters);
                for ($j = 0; $j < $len; $j++) {
                    $targetIdx = $targetIdx * 26 + (ord($colLetters[$j]) - ord('A') + 1);
                }
                $targetIdx -= 1;

                // Isi spasi kosong jika ada sel yang dilompati
                while (count($rowArr) < $targetIdx) {
                    $rowArr[] = '';
                }

                $type = (string)$c['t'];
                $val = isset($c->v) ? (string)$c->v : '';

                if ($type === 's') {
                    $val = $sharedStrings[(int)$val] ?? '';
                } elseif ($type === 'inlineStr' && isset($c->is->t)) {
                    $val = (string)$c->is->t;
                }

                $rowArr[$targetIdx] = trim($val);
            }

            // Simpan jika baris tidak kosong
            if (!empty(array_filter($rowArr, fn($v) => $v !== ''))) {
                $rows[] = $rowArr;
            }
        }

        return $rows;
    }

    /**
     * Parse file CSV dengan deteksi otomatis delimiter (koma, titik koma, tab)
     */
    protected function parseCsv(string $filePath): array
    {
        $content = file_get_contents($filePath);
        // Hilangkan UTF-8 BOM jika ada
        if (substr($content, 0, 3) === chr(0xEF) . chr(0xBB) . chr(0xBF)) {
            $content = substr($content, 3);
        }

        // Deteksi delimiter berdasarkan baris pertama
        $firstLine = strtok($content, "\r\n");
        $semicolons = substr_count($firstLine, ';');
        $commas = substr_count($firstLine, ',');
        $tabs = substr_count($firstLine, "\t");

        $delimiter = ',';
        if ($semicolons > $commas && $semicolons > $tabs) {
            $delimiter = ';';
        } elseif ($tabs > $commas && $tabs > $semicolons) {
            $delimiter = "\t";
        }

        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $content);
        rewind($stream);

        $rows = [];
        while (($data = fgetcsv($stream, 0, $delimiter)) !== false) {
            // Trim setiap nilai
            $trimmed = array_map(fn($v) => trim((string)$v), $data);
            if (!empty(array_filter($trimmed, fn($v) => $v !== ''))) {
                $rows[] = $trimmed;
            }
        }
        fclose($stream);

        return $rows;
    }

    /**
     * Parse tabel HTML jika file .xls berupa file HTML Spreadsheet
     */
    protected function parseHtmlTable(string $filePath): array
    {
        $content = file_get_contents($filePath);
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

        $rows = [];
        $trElements = $dom->getElementsByTagName('tr');
        foreach ($trElements as $tr) {
            $rowArr = [];
            foreach ($tr->childNodes as $cell) {
                if ($cell->nodeName === 'td' || $cell->nodeName === 'th') {
                    $rowArr[] = trim($cell->textContent);
                }
            }
            if (!empty(array_filter($rowArr, fn($v) => $v !== ''))) {
                $rows[] = $rowArr;
            }
        }

        return $rows;
    }

    /**
     * Pemetaan header kolom fleksibel: mencocokkan teks header dengan atribut database
     */
    public function detectColumnMap(array $headerRow): array
    {
        $map = [];
        $aliases = [
            'id_buku'       => ['id buku', 'id_buku', 'kode buku', 'idbuku', 'kode', 'id'],
            'no_inventaris' => ['no inventaris', 'no. inventaris', 'nomor inventaris', 'no_inventaris', 'inventaris', 'no inv', 'no. inv'],
            'judul'         => ['judul buku', 'judul', 'nama buku', 'title'],
            'penulis'       => ['pengarang', 'penulis', 'pengarang / penulis', 'author', 'penulis/pengarang'],
            'penerbit'      => ['penerbit', 'publisher'],
            'tahun_terbit'  => ['tahun terbit', 'thn terbit', 'th terbit', 'tahun', 'year'],
            'stok'          => ['jumlah buku', 'jumlah', 'stok', 'total stok', 'qty', 'total', 'eksemplar'],
            'kelas'         => ['kelas', 'tingkat', 'tingkat kelas', 'peruntukan kelas', 'kls'],
            'kurikulum'     => ['kurikulum', 'kurikulum buku', 'kurikulum sekolah'],
            'sumber'        => ['sumber buku', 'sumber', 'asal perolehan', 'perolehan'],
            'keterangan'    => ['keterangan mapel', 'keterangan', 'mapel', 'mata pelajaran', 'jurusan', 'keahlian'],
            'tanggal_masuk' => ['tanggal masuk', 'tgl masuk', 'tgl_masuk', 'tanggal', 'tgl'],
            'nomor_rak'     => ['nomor rak', 'no rak', 'no. rak', 'rak', 'lokasi rak', 'lokasi'],
            'kategori'      => ['kategori', 'kategori buku', 'jenis buku', 'tipe'],
            'isbn'          => ['isbn', 'no isbn', 'no. isbn'],
            'deskripsi'     => ['deskripsi', 'deskripsi / sinopsis', 'sinopsis', 'keterangan buku', 'ringkasan'],
        ];

        foreach ($headerRow as $colIdx => $colName) {
            $cleanName = strtolower(trim((string)$colName));
            foreach ($aliases as $field => $fieldAliases) {
                if (in_array($cleanName, $fieldAliases)) {
                    $map[$field] = $colIdx;
                    break;
                }
            }
        }

        // Jika tidak ada header yang terdeteksi dengan nama, gunakan default indeks urutan template
        if (!isset($map['judul'])) {
            $defaultFields = array_keys(self::COLUMNS);
            foreach ($defaultFields as $idx => $field) {
                if (isset($headerRow[$idx])) {
                    $map[$field] = $idx;
                }
            }
        }

        return $map;
    }

    /**
     * Memproses baris data menjadi array model buku siap simpan
     */
    public function processRows(array $rawRows, string $mode = 'skip'): array
    {
        if (empty($rawRows)) {
            return [
                'success' => false,
                'message' => 'File spreadsheet kosong atau tidak memuat data yang valid.',
                'imported' => 0,
                'updated'  => 0,
                'skipped'  => 0,
                'errors'   => [],
            ];
        }

        // 1. Cek baris header
        $headerRow = $rawRows[0];
        $isFirstRowHeader = false;
        $sampleHeader = strtolower(implode(' ', $headerRow));
        if (str_contains($sampleHeader, 'judul') || str_contains($sampleHeader, 'buku') || str_contains($sampleHeader, 'pengarang')) {
            $isFirstRowHeader = true;
            $columnMap = $this->detectColumnMap($headerRow);
            $dataRows = array_slice($rawRows, 1);
        } else {
            $columnMap = $this->detectColumnMap($headerRow);
            $dataRows = $rawRows;
        }

        $imported = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];

        $lastId = Buku::max('id') ?? 0;
        $generatedCounter = $lastId + 1;

        foreach ($dataRows as $index => $row) {
            $rowNum = $isFirstRowHeader ? ($index + 2) : ($index + 1);

            // Ekstrak data berdasarkan pemetaan kolom
            $getData = fn($field, $default = '') => isset($columnMap[$field], $row[$columnMap[$field]])
                ? trim((string)$row[$columnMap[$field]])
                : $default;

            $judul        = $getData('judul');
            $penulis      = $getData('penulis');
            $penerbit     = $getData('penerbit');
            $tahunTerbit  = $getData('tahun_terbit');
            $stok         = $getData('stok');
            $kelas        = $getData('kelas');
            $kurikulum    = $getData('kurikulum');
            $noInventaris = $getData('no_inventaris');
            $sumber       = $getData('sumber');
            $keterangan   = $getData('keterangan');
            $tanggalMasuk = $getData('tanggal_masuk');
            $nomorRak     = $getData('nomor_rak');
            $idBuku       = $getData('id_buku');
            $kategori     = $getData('kategori');
            $isbn         = $getData('isbn');
            $deskripsi    = $getData('deskripsi');

            // Validasi wajib (Wajib diisi sesuai aturan SMK)
            if (empty($judul)) {
                $errors[] = "Baris {$rowNum}: Judul buku kosong, baris dilewati.";
                $skipped++;
                continue;
            }

            // Normalisasi data
            if (empty($idBuku)) {
                $idBuku = 'BK-' . str_pad($generatedCounter++, 4, '0', STR_PAD_LEFT);
            }

            if (empty($noInventaris)) {
                $noInventaris = 'INV/' . date('Y') . '/' . str_pad($generatedCounter, 4, '0', STR_PAD_LEFT);
            }

            if (empty($penulis)) {
                $penulis = 'Tim Penulis';
            }

            if (empty($penerbit)) {
                $penerbit = 'Kementerian Pendidikan / Dinas';
            }

            $tahun = (int)preg_replace('/[^0-9]/', '', $tahunTerbit);
            if ($tahun < 1900 || $tahun > (date('Y') + 1)) {
                $tahun = (int)date('Y');
            }

            $jumlahStok = (int)preg_replace('/[^0-9]/', '', $stok);
            if ($jumlahStok < 1) {
                $jumlahStok = 1;
            }

            // Normalisasi kelas (X, XI, XII, Semua)
            $kelasClean = strtoupper(trim($kelas));
            if (str_contains($kelasClean, 'XII') || $kelasClean === '12') {
                $kelasVal = 'XII';
            } elseif (str_contains($kelasClean, 'XI') || $kelasClean === '11') {
                $kelasVal = 'XI';
            } elseif (str_contains($kelasClean, 'X') || $kelasClean === '10') {
                $kelasVal = 'X';
            } else {
                $kelasVal = 'Semua';
            }

            if (empty($kurikulum)) {
                $kurikulum = 'Kurikulum Merdeka';
            }

            if (empty($sumber)) {
                $sumber = 'BOS ' . date('Y');
            }

            if (empty($keterangan)) {
                $keterangan = 'Umum';
            }

            if (empty($nomorRak)) {
                $nomorRak = 'RAK-01';
            }

            // Format tanggal masuk YYYY-MM-DD
            $tglMasukParsed = date('Y-m-d');
            if (!empty($tanggalMasuk)) {
                $time = strtotime($tanggalMasuk);
                if ($time !== false && $time > 0) {
                    $tglMasukParsed = date('Y-m-d', $time);
                }
            }

            // Kategori normalisasi
            $kategoriClean = strtolower(trim($kategori));
            if (str_contains($kategoriClean, 'paket') || str_contains($kategoriClean, 'lks')) {
                $kategoriVal = 'lks_paket';
            } elseif (str_contains($kategoriClean, 'referensi')) {
                $kategoriVal = 'referensi';
            } elseif (str_contains($kategoriClean, 'fiksi')) {
                $kategoriVal = 'karya_fiksi';
            } else {
                $kategoriVal = 'umum';
            }

            // Cek apakah data dengan id_buku atau no_inventaris sudah ada
            $existing = Buku::where('id_buku', $idBuku)
                ->orWhere('no_inventaris', $noInventaris)
                ->first();

            $bookData = [
                'id_buku'       => $idBuku,
                'no_inventaris' => $noInventaris,
                'judul'         => $judul,
                'penulis'       => $penulis,
                'penerbit'      => $penerbit,
                'tahun_terbit'  => $tahun,
                'stok'          => $jumlahStok,
                'stok_tersedia' => $jumlahStok,
                'kelas'         => $kelasVal,
                'kurikulum'     => $kurikulum,
                'sumber'        => $sumber,
                'keterangan'    => $keterangan,
                'tanggal_masuk' => $tglMasukParsed,
                'nomor_rak'     => $nomorRak,
                'kategori'      => $kategoriVal,
                'isbn'          => $isbn ?: null,
                'deskripsi'     => $deskripsi ?: null,
            ];

            if ($existing) {
                if ($mode === 'update') {
                    // Update data yang sudah ada
                    $dipinjam = max(0, $existing->stok - $existing->stok_tersedia);
                    $bookData['stok_tersedia'] = max(0, $jumlahStok - $dipinjam);
                    $existing->update($bookData);
                    $updated++;
                } else {
                    // Skip jika mode = skip
                    $skipped++;
                }
            } else {
                // Buat baru
                Buku::create($bookData);
                $imported++;
            }
        }

        return [
            'success'  => true,
            'imported' => $imported,
            'updated'  => $updated,
            'skipped'  => $skipped,
            'errors'   => $errors,
        ];
    }
}
