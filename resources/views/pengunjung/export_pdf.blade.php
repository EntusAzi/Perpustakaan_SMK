<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Tamu & Laporan Kunjungan - Perpustakaan SMK Negeri 1 Tirtamulya</title>
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
            font-size: 11px;
            color: #0f172a;
            background: #f1f5f9;
            line-height: 1.4;
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

        /* Lembar Kertas A4 Landscape */
        .page-sheet {
            background: #fff;
            width: 297mm;
            min-height: 210mm;
            margin: 20px auto;
            padding: 14mm 16mm;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        /* Kop Surat Resmi */
        .kop-surat {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 16px;
            text-align: center;
        }

        .kop-logo {
            width: 75px;
            height: 75px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .kop-text h4 {
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #334155;
            margin-bottom: 2px;
        }

        .kop-text h2 {
            font-size: 18px;
            font-weight: 800;
            letter-spacing: 0.04em;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .kop-text h3 {
            font-size: 14px;
            font-weight: 700;
            color: #0f766e;
            letter-spacing: 0.03em;
            margin-bottom: 4px;
        }

        .kop-text p {
            font-size: 10px;
            color: #475569;
        }

        /* Judul Laporan */
        .report-title {
            text-align: center;
            margin-bottom: 16px;
        }

        .report-title h1 {
            font-size: 15px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #0f172a;
            text-decoration: underline;
        }

        .report-title .meta {
            font-size: 11px;
            color: #475569;
            margin-top: 4px;
        }

        /* Metadata Filter */
        .filter-summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 8px 14px;
            margin-bottom: 14px;
            font-size: 11px;
        }

        /* Tabel Data Pengunjung */
        table.table-report {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
            margin-bottom: 20px;
        }

        table.table-report th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: 700;
            padding: 8px 6px;
            border: 1px solid #94a3b8;
            text-align: left;
            text-transform: uppercase;
            font-size: 10px;
        }

        table.table-report td {
            padding: 7px 6px;
            border: 1px solid #cbd5e1;
            vertical-align: top;
        }

        table.table-report tr:nth-child(even) td {
            background-color: #fafbfc;
        }

        .text-center { text-align: center; }
        .text-right  { text-align: right; }
        .font-mono   { font-family: monospace; }
        .font-bold   { font-weight: 700; }

        .badge-tipe {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9.5px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .badge-guru {
            background: #dbeafe;
            color: #1e40af;
        }
        .badge-siswa {
            background: #f1f5f9;
            color: #475569;
        }

        /* Tanda Tangan */
        .ttd-section {
            margin-top: 24px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }

        .ttd-box {
            width: 260px;
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
                size: A4 landscape;
                margin: 10mm 10mm;
            }

            body {
                background: #fff !important;
                color: #000 !important;
                font-size: 9.5px;
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

            table.table-report {
                font-size: 9.5px;
            }

            table.table-report th {
                background-color: #e2e8f0 !important;
                color: #000 !important;
                border-color: #64748b !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            table.table-report td {
                border-color: #94a3b8 !important;
            }

            tr {
                page-break-inside: avoid;
            }

            thead {
                display: table-header-group;
            }
        }
    </style>
</head>
<body>

{{-- ACTION BAR LAYAR --}}
<div class="action-bar">
    <div style="display:flex; align-items:center; gap:12px;">
        <span style="font-size:14px; font-weight:700;">🖨️ Pratinjau Cetak / Export PDF Resmi</span>
        <span style="font-size:12px; color:rgba(255,255,255,0.7);">Buku Tamu & Laporan Kunjungan Perpustakaan SMK Negeri 1 Tirtamulya</span>
    </div>

    <div style="display:flex; gap:10px;">
        <a href="{{ route('pengunjung.index') }}" class="action-btn btn-back">
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
        <h1>Buku Tamu & Laporan Rekapitulasi Kunjungan Perpustakaan</h1>
        <div class="meta">
            Dicetak pada: {{ $filterInfo['tanggal'] }} | Waktu: {{ date('H:i:s') }} WIB
        </div>
    </div>

    {{-- SUMMARY FILTER --}}
    <div class="filter-summary">
        <div>
            <strong>Periode / Tanggal:</strong> {{ $filterInfo['periode'] }} &nbsp;|&nbsp;
            <strong>Filter Tipe:</strong> {{ $filterInfo['tipe'] }}
            @if($filterInfo['search'])
                &nbsp;|&nbsp; <strong>Pencarian:</strong> "{{ $filterInfo['search'] }}"
            @endif
        </div>
        <div>
            <strong>Total Pengunjung:</strong> <span class="font-bold">{{ number_format($totalPengunjung) }} Orang</span> &nbsp;
            (<strong>Guru/Staf:</strong> {{ number_format($totalGuru) }} | <strong>Siswa:</strong> {{ number_format($totalSiswa) }})
        </div>
    </div>

    {{-- TABEL DATA PENGUNJUNG --}}
    <table class="table-report">
        <thead>
            <tr>
                <th class="text-center" style="width: 32px;">No</th>
                <th style="width: 170px;">Nama Pengunjung</th>
                <th class="text-center" style="width: 75px;">Tipe</th>
                <th style="width: 110px;">Kelas / Jabatan</th>
                <th style="width: 100px;">NIS / NIP</th>
                <th class="text-center" style="width: 95px;">Tanggal</th>
                <th class="text-center" style="width: 75px;">Waktu</th>
                <th>Keperluan Kunjungan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($visitors as $index => $v)
            @php
                $cleanNama = $v->nama;
                if (!empty($v->nis_nip)) {
                    $rawNis = preg_replace('/[^0-9]/', '', $v->nis_nip);
                    if (!empty($rawNis)) {
                        $cleanNama = preg_replace('/\s*' . preg_quote($rawNis, '/') . '$/', '', $cleanNama);
                    }
                    $cleanNama = preg_replace('/\s*' . preg_quote($v->nis_nip, '/') . '$/i', '', $cleanNama);
                }
            @endphp
            <tr>
                <td class="text-center font-bold">{{ $index + 1 }}</td>
                <td>
                    <span class="font-bold">{{ trim($cleanNama) }}</span>
                </td>
                <td class="text-center">
                    <span class="badge-tipe badge-{{ $v->tipe }}">{{ ucfirst($v->tipe) }}</span>
                </td>
                <td>{{ $v->kelas_jabatan ?? '-' }}</td>
                <td class="font-mono">{{ $v->nis_nip ?? '-' }}</td>
                <td class="text-center">
                    {{ $v->tanggal_kunjungan ? \Carbon\Carbon::parse($v->tanggal_kunjungan)->format('d/m/Y') : '-' }}
                </td>
                <td class="text-center font-mono">
                    {{ $v->waktu_masuk ? \Carbon\Carbon::parse($v->waktu_masuk)->format('H:i') . ' WIB' : '-' }}
                </td>
                <td>{{ $v->keperluan ?? 'Berkunjung ke perpustakaan' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center" style="padding: 24px; color: #64748b;">
                    Tidak ada data pengunjung yang sesuai dengan filter yang dipilih.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

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
            <div>Karawang, {{ $filterInfo['tanggal'] }}</div>
            <div style="font-weight:600;">Kepala / Pengelola Perpustakaan</div>
            <div class="ttd-space"></div>
            <div class="ttd-name">{{ auth()->user()->name ?? 'Petugas Perpustakaan' }}</div>
            <div class="ttd-nip">NIP/NUPTK: 19850720 201101 2 008</div>
        </div>
    </div>
</div>

<script>
    // Auto buka dialog cetak jika ada parameter autoprint=1
    if (new URLSearchParams(window.location.search).get('autoprint') === '1') {
        window.addEventListener('load', () => window.print());
    }
</script>

</body>
</html>
