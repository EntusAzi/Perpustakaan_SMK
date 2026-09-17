<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjam;
use App\Models\Peminjaman;
use App\Models\Pengunjung;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        // Total Buku
        $totalBuku = Buku::count();
        $totalBukuLastMonth = Buku::where('created_at', '<', $lastMonth)->count();
        $totalBukuChange = $totalBukuLastMonth > 0
            ? round((($totalBuku - $totalBukuLastMonth) / $totalBukuLastMonth) * 100)
            : 0;

        // Buku Dipinjam
        $bukuDipinjam = Peminjaman::where('status', 'dipinjam')->count();
        $bukuDipinjamLastMonth = Peminjaman::where('status', 'dipinjam')
            ->where('created_at', '<', $lastMonth)->count();
        $bukuDipinjamChange = $bukuDipinjamLastMonth > 0
            ? round((($bukuDipinjam - $bukuDipinjamLastMonth) / $bukuDipinjamLastMonth) * 100)
            : 5;

        // Pengunjung Hari Ini
        $pengunjungHariIni = Pengunjung::whereDate('tanggal_kunjungan', $now->toDateString())->count();
        $pengunjungLastMonth = Pengunjung::whereDate('tanggal_kunjungan', $lastMonth->toDateString())->count();
        $pengunjungChange = $pengunjungLastMonth > 0
            ? round((($pengunjungHariIni - $pengunjungLastMonth) / $pengunjungLastMonth) * 100)
            : 24;

        // Peminjam Aktif
        $peminjamAktif = Peminjam::whereHas('peminjaman', function ($q) {
            $q->where('status', 'dipinjam');
        })->count();

        // Peminjaman Terbaru (5 data)
        $peminjamanTerbaru = Peminjaman::with(['peminjam', 'buku'])
            ->latest()
            ->take(5)
            ->get();

        // Pengunjung Terbaru (5 data)
        $pengunjungTerbaru = Pengunjung::latest('tanggal_kunjungan')
            ->latest('waktu_masuk')
            ->latest('id')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalBuku', 'totalBukuChange',
            'bukuDipinjam', 'bukuDipinjamChange',
            'pengunjungHariIni', 'pengunjungChange',
            'peminjamAktif',
            'peminjamanTerbaru',
            'pengunjungTerbaru'
        ));
    }
}
