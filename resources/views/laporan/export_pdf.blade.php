<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Sirkulasi & Evaluasi - Perpustakaan SMK Negeri 1 Tirtamulya</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', Arial, sans-serif;
            font-size: 11.5px;
            color: #0f172a;
            background: #f1f5f9;
            line-height: 1.5;
        }

        /* Floating Action Bar (Layar Saja) */
        .action-bar {
            position: sticky;
            top: 0;
            background: #0f172a;
            color: #fff;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.15);
            z-index: 1000;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: all 0.2s;
        }

        .btn-print {
            background: #00c9a7;
            color: #0f172a;
        }
        .btn-print:hover {
            background: #00a589;
            color: #fff;
        }

        .btn-back {
            background: rgba(255,255,255,0.15);
            color: #fff;
        }
        .btn-back:hover {
            background: rgba(255,255,255,0.25);
        }

        /* Lembar Kertas A4 Portrait */
        .page-sheet {
            background: #fff;
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            padding: 16mm 18mm;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        /* Kop Surat Resmi */
        .kop-surat {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 18px;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 18px;
            text-align: center;
        }

        .kop-logo {
            width: 70px;
            height: 70px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .kop-text h4 {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #334155;
            margin-bottom: 2px;
        }

        .kop-text h2 {
            font-size: 16px;
            font-weight: 800;
            letter-spacing: 0.04em;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .kop-text h3 {
            font-size: 13px;
            font-weight: 700;
            color: #0f766e;
            letter-spacing: 0.03em;
            margin-bottom: 3px;
        }

        .kop-text p {
            font-size: 9.5px;
            color: #475569;
        }

        /* Judul Laporan */
        .report-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .report-title h1 {
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #0f172a;
            text-decoration: underline;
        }

        .report-title .meta {
            font-size: 10.5px;
            color: #475569;
            margin-top: 4px;
        }

        /* Blok Bulanan Sesuai Format Buku */
        .month-block {
            margin-bottom: 24px;
            page-break-inside: avoid;
        }

        .month-title {
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.04em;
            color: #0f172a;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        /* Tabel Laporan Sirkulasi */
        table.table-report {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
            margin-bottom: 10px;
        }

        table.table-report th {
            background-color: #f8fafc;
            color: #0f172a;
            font-weight: 700;
            padding: 6px 4px;
            border: 1px solid #000;
            text-align: center;
            font-size: 10px;
        }

        table.table-report td {
            padding: 5px 6px;
            border: 1px solid #000;
            vertical-align: middle;
        }

        .text-center { text-align: center; }
        .text-left   { text-align: left; }
        .text-right  { text-align: right; }
        .font-bold   { font-weight: 700; }

        /* Sirkulasi & Evaluasi Section */
        .report-section-title {
            font-weight: 800;
            font-size: 11px;
            color: #0f172a;
            text-transform: uppercase;
            margin-top: 8px;
            margin-bottom: 2px;
        }

        .evaluasi-box {
            margin-bottom: 12px;
        }

        .evaluasi-row {
            display: flex;
            margin-bottom: 2px;
            font-size: 11px;
        }

        .evaluasi-label {
            width: 190px;
            position: relative;
        }

        .evaluasi-row-underline {
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
            margin-bottom: 4px;
            display: inline-flex;
            width: 320px;
        }

        .evaluasi-val {
            font-weight: 500;
        }

        /* Tanda Tangan */
        .ttd-section {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }

        .ttd-box {
            width: 240px;
            text-align: center;
            font-size: 11px;
        }

        .ttd-space {
            height: 60px;
        }

        .ttd-name {
            font-weight: 700;
            text-decoration: underline;
            color: #0f172a;
        }

        .ttd-nip {
            font-size: 10px;
            color: #475569;
        }

        /* PENGATURAN CETAK (@MEDIA PRINT) */
        @media print {
            @page {
                size: A4 portrait;
                margin: 12mm 14mm;
            }

            body {
                background: #fff !important;
                color: #000 !important;
            }

            .action-bar {
                display: none !important;
            }

            .page-sheet {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
            }

            table.table-report th {
                background-color: #f1f5f9 !important;
                color: #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .month-block {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

{{-- ACTION BAR LAYAR --}}
<div class="action-bar">
    <div style="display:flex; align-items:center; gap:12px;">
        <span style="font-size:14px; font-weight:700;">🖨️ Pratinjau Cetak / Export PDF Resmi</span>
        <span style="font-size:12px; color:rgba(255,255,255,0.7);">Laporan Sirkulasi & Evaluasi Bulanan Perpustakaan</span>
    </div>

    <div style="display:flex; gap:10px;">
        <a href="{{ route('laporan.index') }}" class="action-btn btn-back">
            ✕ Tutup / Kembali
        </a>
        <button onclick="window.print()" class="action-btn btn-print">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>
            </svg>
            Cetak / Simpan PDF
        </button>
    </div>
</div>

<div class="page-sheet">
    {{-- KOP SURAT SEKOLAH --}}
    <div class="kop-surat">
        <img src="{{ asset('assets/images/Logo Sekolah_No_Bg.png') }}" alt="Logo SMK" class="kop-logo">
        <div class="kop-text">
            <h4>Pemerintah Daerah Provinsi Jawa Barat • Dinas Pendidikan</h4>
            <h4>Cabang Dinas Pendidikan Wilayah IV</h4>
            <h2>SMK NEGERI 1 TIRTAMULYA</h2>
            <h3>PERPUSTAKAAN SEKOLAH</h3>
            <p>Jl. Raya Parakan - Tirtamulya, Desa Parakan, Kec. Tirtamulya, Kab. Karawang, Jawa Barat 41372</p>
            <p>Email: perpustakaan@smkn1tirtamulya.sch.id | Website: smkn1tirtamulya.sch.id</p>
        </div>
    </div>

    {{-- JUDUL LAPORAN --}}
    <div class="report-title">
        <h1>Laporan Rekapitulasi Sirkulasi & Evaluasi Perpustakaan</h1>
        <div class="meta">
            Periode: {{ $startDate }} s/d {{ $endDate }} &nbsp;|&nbsp; Dicetak pada: {{ date('d F Y') }}
        </div>
    </div>

    {{-- BLOK LAPORAN PER BULAN (PERSIS FORMAT GAMBAR BUKU RESMI) --}}
    @foreach($laporanBulanan as $item)
    <div class="month-block">
        <div class="month-title">{{ $item['bulan_label'] }}</div>

        <table class="table-report">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 38px;">No</th>
                    <th rowspan="2" style="width: 170px; text-align: left; padding-left: 8px;">Jenis Anggota</th>
                    <th colspan="2">Jumlah Pengunjung</th>
                    <th colspan="2">Jumlah Peminjam</th>
                </tr>
                <tr>
                    <th style="width: 75px;">Online</th>
                    <th style="width: 75px;">Offline</th>
                    <th style="width: 75px;">Online</th>
                    <th style="width: 75px;">Offline</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td>Siswa</td>
                    <td class="text-center">-</td>
                    <td class="text-center">{{ $item['pengunjung']['siswa'] ?: '-' }}</td>
                    <td class="text-center">-</td>
                    <td class="text-center">{{ $item['peminjam']['siswa'] ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="text-center">2</td>
                    <td>Guru/karyawan</td>
                    <td class="text-center">-</td>
                    <td class="text-center">{{ $item['pengunjung']['guru'] ?: '-' }}</td>
                    <td class="text-center">-</td>
                    <td class="text-center">{{ $item['peminjam']['guru'] ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="text-center">3</td>
                    <td>Kepsek</td>
                    <td class="text-center">-</td>
                    <td class="text-center">-</td>
                    <td class="text-center">-</td>
                    <td class="text-center">-</td>
                </tr>
                <tr>
                    <td class="text-center">4</td>
                    <td>Masyarakat</td>
                    <td class="text-center">-</td>
                    <td class="text-center">-</td>
                    <td class="text-center">-</td>
                    <td class="text-center">-</td>
                </tr>
                <tr class="font-bold">
                    <td colspan="2" class="text-center">Jumlah</td>
                    <td class="text-center">-</td>
                    <td class="text-center">{{ $item['pengunjung']['total'] ?: '-' }}</td>
                    <td class="text-center">-</td>
                    <td class="text-center">{{ $item['peminjam']['total'] ?: '-' }}</td>
                </tr>
            </tbody>
        </table>

        {{-- SIRKULASI --}}
        <div class="report-section-title">SIRKULASI</div>
        <div style="font-size: 11px; margin-bottom: 6px;">
            Jumlah buku terpinjam &nbsp;: {{ $item['sirkulasi']['buku_terpinjam'] }} eksemplar
        </div>

        {{-- EVALUASI --}}
        <div class="report-section-title">EVALUASI</div>
        <div class="evaluasi-box">
            <div class="evaluasi-row evaluasi-row-underline">
                <div class="evaluasi-label">Rata-rata pengunjung perhari</div>
                <div class="evaluasi-val">: {{ $item['evaluasi']['rata_pengunjung'] ?: '-' }} orang</div>
            </div>
            <div class="evaluasi-row">
                <div class="evaluasi-label">Rata-rata peminjam perhari</div>
                <div class="evaluasi-val">: {{ $item['evaluasi']['rata_peminjam'] ?: '-' }} orang</div>
            </div>
            <div class="evaluasi-row">
                <div class="evaluasi-label">Rata-rata buku terpinjam</div>
                <div class="evaluasi-val">: {{ $item['evaluasi']['rata_buku'] ?: '-' }} eksemplar/hari</div>
            </div>
        </div>
    </div>
    @endforeach

    {{-- TANDA TANGAN RESMI --}}
    <div class="ttd-section">
        <div class="ttd-box">
            <div>Mengetahui,</div>
            <div style="font-weight:600;">Kepala SMK Negeri 1 Tirtamulya</div>
            <div class="ttd-space"></div>
            <div class="ttd-name">H. Dedi Supriyadi, S.Pd., M.M.</div>
            <div class="ttd-nip">NIP. 19680512 199303 1 005</div>
        </div>

        <div class="ttd-box">
            <div>Karawang, {{ date('d F Y') }}</div>
            <div style="font-weight:600;">Kepala / Pengelola Perpustakaan</div>
            <div class="ttd-space"></div>
            <div class="ttd-name">{{ auth()->user()->name ?? 'Petugas Perpustakaan' }}</div>
            <div class="ttd-nip">NIP/NUPTK: 19850720 201101 2 008</div>
        </div>
    </div>
</div>

<script>
    if (new URLSearchParams(window.location.search).get('autoprint') === '1') {
        window.addEventListener('load', () => window.print());
    }
</script>

</body>
</html>
