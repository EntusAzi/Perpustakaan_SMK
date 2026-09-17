<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjam;
use App\Models\Peminjaman;
use App\Models\Pengunjung;
use App\Services\PengunjungSpreadsheetService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PengunjungController extends Controller
{
    /**
     * Query filter terpusat untuk Pengunjung
     */
    private function filterPengunjungQuery(Request $request)
    {
        $today = Carbon::today()->toDateString();
        $query = Pengunjung::query();

        if ($request->filled('tipe') && $request->tipe !== 'semua') {
            $query->where('tipe', $request->tipe);
        }

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // Filter Tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_kunjungan', $request->tanggal);
        } elseif ($request->get('filter_waktu') === 'semua') {
            // Tampilkan semua riwayat
        } elseif ($request->filled('search')) {
            // Jika sedang mencari nama, cari di semua tanggal agar mudah ditemukan
        } else {
            // Default: tampilkan pengunjung hari ini
            $query->whereDate('tanggal_kunjungan', $today);
        }

        return $query->oldest('tanggal_kunjungan')->oldest('waktu_masuk')->orderBy('id', 'asc');
    }

    public function index(Request $request)
    {
        $today = Carbon::today()->toDateString();

        $pengunjung = $this->filterPengunjungQuery($request)->paginate(10);

        $totalHariIni = Pengunjung::whereDate('tanggal_kunjungan', $today)->count();
        $totalGuru    = Pengunjung::whereDate('tanggal_kunjungan', $today)->where('tipe', 'guru')->count();
        $totalSiswa   = Pengunjung::whereDate('tanggal_kunjungan', $today)->where('tipe', 'siswa')->count();

        return view('pengunjung.data_pengunjung', compact(
            'pengunjung', 'today', 'totalHariIni', 'totalGuru', 'totalSiswa'
        ));
    }

    public function create()
    {
        $peminjam = Peminjam::orderBy('nama')->get();
        $buku = Buku::where('stok_tersedia', '>', 0)->orderBy('judul')->get();

        return view('pengunjung.tambah_pengunjung', compact('peminjam', 'buku'));
    }

    public function store(Request $request)
    {
        if ($request->has('peminjam_id') && (!is_numeric($request->peminjam_id) || empty($request->peminjam_id))) {
            $request->merge(['peminjam_id' => null]);
        }

        $validated = $request->validate([
            'peminjam_id'       => 'nullable|exists:peminjam,id',
            'nama'              => 'required|string|max:255',
            'tipe'              => 'required|in:guru,siswa',
            'kelas_jabatan'     => 'nullable|string|max:100',
            'nis_nip'           => 'nullable|string|max:50',
            'telepon'           => 'nullable|string|max:20',
            'alamat'            => 'nullable|string',
            'tanggal_kunjungan' => 'nullable|date',
            'waktu_masuk'       => 'nullable',
            'keperluan'         => 'nullable|string|max:255',
            'meminjam_buku'     => 'nullable',
            'buku_id'           => 'nullable|required_if:meminjam_buku,1|exists:buku,id',
            'tanggal_pinjam'    => 'nullable|required_if:meminjam_buku,1|date',
            'tanggal_kembali'   => 'nullable|required_if:meminjam_buku,1|date|after_or_equal:tanggal_pinjam',
            'catatan'           => 'nullable|string',
        ]);

        // 1. Simpan atau perbarui data master Peminjam (anggota perpustakaan)
        $peminjam = null;
        if (!empty($validated['peminjam_id'])) {
            $peminjam = Peminjam::find($validated['peminjam_id']);
            if ($peminjam) {
                $peminjam->update([
                    'nama'          => $validated['nama'],
                    'tipe'          => $validated['tipe'],
                    'kelas_jabatan' => $validated['kelas_jabatan'] ?? $peminjam->kelas_jabatan,
                    'nis_nip'       => $validated['nis_nip'] ?? $peminjam->nis_nip,
                    'telepon'       => $validated['telepon'] ?? $peminjam->telepon,
                    'alamat'        => $validated['alamat'] ?? $peminjam->alamat,
                ]);
            }
        }

        if (!$peminjam) {
            $peminjam = Peminjam::firstOrCreate(
                [
                    'nama' => $validated['nama'],
                    'tipe' => $validated['tipe'],
                ],
                [
                    'kelas_jabatan' => $validated['kelas_jabatan'] ?? null,
                    'nis_nip'       => $validated['nis_nip'] ?? null,
                    'telepon'       => $validated['telepon'] ?? null,
                    'alamat'        => $validated['alamat'] ?? null,
                ]
            );
        }

        $now = now();
        $isPinjam = $request->filled('meminjam_buku') && !empty($validated['buku_id']);
        $keperluan = $validated['keperluan'] ?? 'Berkunjung & membaca buku di perpustakaan';

        // 2. Jika meminjam buku, catat transaksi peminjaman & kurangi stok
        if ($isPinjam) {
            $buku = Buku::findOrFail($validated['buku_id']);
            if ($buku->stok_tersedia < 1) {
                return back()->withErrors(['buku_id' => 'Stok buku "' . $buku->judul . '" sedang habis.'])->withInput();
            }

            Peminjaman::create([
                'peminjam_id'    => $peminjam->id,
                'buku_id'        => $buku->id,
                'tanggal_pinjam' => $validated['tanggal_pinjam'] ?? $now->toDateString(),
                'tanggal_kembali'=> $validated['tanggal_kembali'] ?? $now->copy()->addDays(7)->toDateString(),
                'catatan'        => $validated['catatan'] ?? null,
                'status'         => 'dipinjam',
            ]);
            $buku->decrement('stok_tersedia');

            $keperluan = 'Meminjam buku: ' . $buku->judul;
        }

        // 3. Catat di data kunjungan perpustakaan
        $tanggalKunjungan = $validated['tanggal_kunjungan'] ?? ($validated['tanggal_pinjam'] ?? $now->toDateString());
        $waktuMasuk = !empty($validated['waktu_masuk']) ? $validated['waktu_masuk'] : $now->format('H:i:s');

        Pengunjung::create([
            'nama'              => $peminjam->nama,
            'tipe'              => $peminjam->tipe,
            'kelas_jabatan'     => $peminjam->kelas_jabatan,
            'nis_nip'           => $peminjam->nis_nip,
            'tanggal_kunjungan' => $tanggalKunjungan,
            'waktu_masuk'       => $waktuMasuk,
            'keperluan'         => $keperluan,
        ]);

        $pesan = $isPinjam
            ? 'Kunjungan ' . $peminjam->nama . ' berhasil dicatat dan peminjaman buku berhasil disimpan!'
            : 'Data pengunjung ' . $peminjam->nama . ' berhasil dicatat dan otomatis terdaftar sebagai anggota perpustakaan!';

        return redirect()->route('pengunjung.index')->with('success', $pesan);
    }

    public function show(Pengunjung $pengunjung)
    {
        $member = Peminjam::where('nama', $pengunjung->nama)->first();
        return view('pengunjung.detail_pengunjung', compact('pengunjung', 'member'));
    }

    public function edit(Pengunjung $pengunjung)
    {
        return view('pengunjung.edit_pengunjung', compact('pengunjung'));
    }

    public function update(Request $request, Pengunjung $pengunjung)
    {
        $validated = $request->validate([
            'nama'              => 'required|string|max:255',
            'tipe'              => 'required|in:guru,siswa',
            'kelas_jabatan'     => 'nullable|string|max:100',
            'nis_nip'           => 'nullable|string|max:50',
            'tanggal_kunjungan' => 'required|date',
            'waktu_masuk'       => 'required',
            'waktu_keluar'      => 'nullable',
            'keperluan'         => 'nullable|string|max:255',
        ]);

        $oldNama = $pengunjung->nama;
        $pengunjung->update($validated);

        // Perbarui data master anggota di tabel peminjam jika ada
        $member = Peminjam::where('nama', $oldNama)->first();
        if ($member) {
            $member->update([
                'nama'          => $validated['nama'],
                'tipe'          => $validated['tipe'],
                'kelas_jabatan' => $validated['kelas_jabatan'] ?? $member->kelas_jabatan,
                'nis_nip'       => $validated['nis_nip'] ?? $member->nis_nip,
            ]);
        }

        return redirect()->route('pengunjung.index')->with('success', 'Data pengunjung berhasil diperbarui!');
    }

    public function destroy(Pengunjung $pengunjung)
    {
        $pengunjung->delete();
        return redirect()->route('pengunjung.index')->with('success', 'Data pengunjung berhasil dihapus!');
    }

    /**
     * Unduh file data pengunjung ke format Excel (.xls) atau CSV (.csv)
     */
    public function exportExcel(Request $request, PengunjungSpreadsheetService $service)
    {
        $format = $request->get('format', 'xls') === 'csv' ? 'csv' : 'xls';
        $visitors = $this->filterPengunjungQuery($request)->get();

        $fileData = $service->exportPengunjung($visitors, $format);

        return response($fileData['content'])
            ->header('Content-Type', $fileData['mime'])
            ->header('Content-Disposition', 'attachment; filename="' . $fileData['filename'] . '"')
            ->header('Cache-Control', 'max-age=0');
    }

    /**
     * Tampilkan pratinjau cetak / Export PDF Resmi (A4 Landscape)
     */
    public function exportPdf(Request $request)
    {
        $visitors = $this->filterPengunjungQuery($request)->get();

        $periodeText = 'Hari Ini (' . Carbon::today()->locale('id')->isoFormat('D MMMM YYYY') . ')';
        if ($request->filled('tanggal')) {
            $periodeText = Carbon::parse($request->tanggal)->locale('id')->isoFormat('D MMMM YYYY');
        } elseif ($request->get('filter_waktu') === 'semua') {
            $periodeText = 'Semua Riwayat Kunjungan';
        }

        $filterInfo = [
            'tipe'     => $request->filled('tipe') && $request->tipe !== 'semua' ? ucfirst($request->tipe) : 'Semua Tipe',
            'periode'  => $periodeText,
            'search'   => $request->filled('search') ? $request->search : null,
            'tanggal'  => Carbon::now()->locale('id')->isoFormat('D MMMM YYYY'),
        ];

        $totalPengunjung = $visitors->count();
        $totalGuru       = $visitors->where('tipe', 'guru')->count();
        $totalSiswa      = $visitors->where('tipe', 'siswa')->count();

        return view('pengunjung.export_pdf', compact('visitors', 'filterInfo', 'totalPengunjung', 'totalGuru', 'totalSiswa'));
    }
}

