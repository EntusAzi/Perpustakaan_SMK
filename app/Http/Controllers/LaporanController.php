<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjam;
use App\Models\Peminjaman;
use App\Models\Pengunjung;
use App\Services\LaporanSpreadsheetService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Laporan Bulanan Buku (last 6 months)
        $bukuBulanan = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $bukuBulanan[] = [
                'label' => $month->format('M Y'),
                'count' => Buku::whereYear('created_at', $month->year)
                               ->whereMonth('created_at', $month->month)
                               ->count(),
            ];
        }

        // Laporan Bulanan Pengunjung
        $pengunjungBulanan = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $pengunjungBulanan[] = [
                'label' => $month->format('M Y'),
                'count' => Pengunjung::whereYear('tanggal_kunjungan', $month->year)
                                     ->whereMonth('tanggal_kunjungan', $month->month)
                                     ->count(),
            ];
        }

        // Laporan Bulanan Peminjam
        $peminjamanBulanan = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $peminjamanBulanan[] = [
                'label' => $month->format('M Y'),
                'count' => Peminjaman::whereYear('tanggal_pinjam', $month->year)
                                     ->whereMonth('tanggal_pinjam', $month->month)
                                     ->count(),
            ];
        }

        // Rekap Seluruh Buku (by kategori)
        $rekapBuku = [
            ['label' => 'LKS/Paket',   'count' => Buku::where('kategori', 'lks_paket')->count()],
            ['label' => 'Referensi',   'count' => Buku::where('kategori', 'referensi')->count()],
            ['label' => 'Karya Fiksi', 'count' => Buku::where('kategori', 'karya_fiksi')->count()],
            ['label' => 'Umum',        'count' => Buku::where('kategori', 'umum')->count()],
        ];

        $totalBukuTerbaru = Buku::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalPengunjung  = Pengunjung::whereBetween('tanggal_kunjungan', [$startDate, $endDate])->count();
        $totalPeminjaman  = Peminjaman::whereBetween('tanggal_pinjam', [$startDate, $endDate])->count();
        $totalBuku        = Buku::count();

        return view('laporan.index', compact(
            'startDate', 'endDate',
            'bukuBulanan', 'pengunjungBulanan', 'peminjamanBulanan', 'rekapBuku',
            'totalBukuTerbaru', 'totalPengunjung', 'totalPeminjaman', 'totalBuku'
        ));
    }

    /**
     * Hitung rekap bulanan untuk export Excel & PDF persis format buku fisik
     */
    private function buildLaporanBulanan(string $startDate, string $endDate): array
    {
        $bulanIndo = [
            1 => 'JANUARI', 2 => 'FEBRUARI', 3 => 'MARET', 4 => 'APRIL',
            5 => 'MEI', 6 => 'JUNI', 7 => 'JULI', 8 => 'AGUSTUS',
            9 => 'SEPTEMBER', 10 => 'OKTOBER', 11 => 'NOVEMBER', 12 => 'DESEMBER'
        ];

        $cursor = Carbon::parse($startDate)->startOfMonth();
        $end    = Carbon::parse($endDate)->endOfMonth();

        if ($cursor->gt($end)) {
            $cursor = Carbon::parse($endDate)->startOfMonth();
            $end    = Carbon::parse($startDate)->endOfMonth();
        }

        $result = [];

        while ($cursor->lte($end)) {
            $mStart    = $cursor->copy()->startOfMonth();
            $mEnd      = $cursor->copy()->endOfMonth();
            $mStartStr = $mStart->toDateString();
            $mEndStr   = $mEnd->toDateString();

            // 1. Data Pengunjung
            $pengunjungSiswa = Pengunjung::whereBetween('tanggal_kunjungan', [$mStartStr, $mEndStr])
                ->where('tipe', 'siswa')
                ->count();

            $pengunjungGuru = Pengunjung::whereBetween('tanggal_kunjungan', [$mStartStr, $mEndStr])
                ->where('tipe', 'guru')
                ->count();

            $totalPengunjung = $pengunjungSiswa + $pengunjungGuru;

            // 2. Data Peminjam
            $peminjamSiswa = Peminjaman::whereBetween('tanggal_pinjam', [$mStartStr, $mEndStr])
                ->whereHas('peminjam', function ($q) {
                    $q->where('tipe', 'siswa');
                })
                ->count();

            $peminjamGuru = Peminjaman::whereBetween('tanggal_pinjam', [$mStartStr, $mEndStr])
                ->whereHas('peminjam', function ($q) {
                    $q->where('tipe', 'guru');
                })
                ->count();

            $totalPeminjam = $peminjamSiswa + $peminjamGuru;

            // 3. Sirkulasi (Buku terpinjam)
            $bukuTerpinjam = Peminjaman::whereBetween('tanggal_pinjam', [$mStartStr, $mEndStr])->count();

            // 4. Evaluasi (Rata-rata per hari kerja efektif sekolah Senin-Jumat)
            $weekdays = 0;
            $temp = $mStart->copy();
            while ($temp->lte($mEnd)) {
                if (!$temp->isWeekend()) {
                    $weekdays++;
                }
                $temp->addDay();
            }
            $effectiveDays = max(1, $weekdays);

            $rataPengunjung = $totalPengunjung > 0 ? (int) max(1, round($totalPengunjung / $effectiveDays)) : 0;
            $rataPeminjam   = $totalPeminjam > 0 ? (int) max(1, round($totalPeminjam / $effectiveDays)) : 0;
            $rataBuku       = $bukuTerpinjam > 0 ? (int) max(1, round($bukuTerpinjam / $effectiveDays)) : 0;

            $namaBulan = $bulanIndo[$cursor->month] ?? strtoupper($cursor->format('F'));
            $bulanLabel = 'BULAN ' . $namaBulan . ' TAHUN ' . $cursor->year;

            $result[] = [
                'bulan_label' => $bulanLabel,
                'pengunjung'  => [
                    'siswa' => $pengunjungSiswa,
                    'guru'  => $pengunjungGuru,
                    'total' => $totalPengunjung,
                ],
                'peminjam'    => [
                    'siswa' => $peminjamSiswa,
                    'guru'  => $peminjamGuru,
                    'total' => $totalPeminjam,
                ],
                'sirkulasi'   => [
                    'buku_terpinjam' => $bukuTerpinjam,
                ],
                'evaluasi'    => [
                    'rata_pengunjung' => $rataPengunjung,
                    'rata_peminjam'   => $rataPeminjam,
                    'rata_buku'       => $rataBuku,
                ],
            ];

            $cursor->addMonth();
        }

        return $result;
    }

    /**
     * Export Excel (.xls) berformat laporan sirkulasi & evaluasi persis buku fisik
     */
    public function exportExcel(Request $request, LaporanSpreadsheetService $service)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $laporanBulanan = $this->buildLaporanBulanan($startDate, $endDate);
        $fileData = $service->exportLaporan($laporanBulanan, $startDate, $endDate);

        return response($fileData['content'])
            ->header('Content-Type', $fileData['mime'])
            ->header('Content-Disposition', 'attachment; filename="' . $fileData['filename'] . '"')
            ->header('Cache-Control', 'max-age=0');
    }

    /**
     * Cetak / Export PDF Resmi (A4 Portrait) berformat laporan sirkulasi & evaluasi persis buku fisik
     */
    public function exportPdf(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $laporanBulanan = $this->buildLaporanBulanan($startDate, $endDate);

        return view('laporan.export_pdf', compact('laporanBulanan', 'startDate', 'endDate'));
    }
}
