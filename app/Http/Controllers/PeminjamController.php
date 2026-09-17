<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjam;
use App\Models\Peminjaman;
use App\Models\Pengunjung;
use App\Services\PeminjamSpreadsheetService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PeminjamController extends Controller
{
    /**
     * Query filter terpusat untuk Peminjam
     */
    private function filterPeminjamQuery(Request $request)
    {
        $query = Peminjam::withCount([
            'peminjaman as buku_dipinjam' => fn($q) => $q->where('status', 'dipinjam'),
            'peminjaman as total_pinjam',
        ]);

        if ($request->filled('tipe') && $request->tipe !== 'semua') {
            $query->where('tipe', $request->tipe);
        }

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        return $query->oldest()->orderBy('id', 'asc');
    }

    public function index(Request $request)
    {
        $peminjam = $this->filterPeminjamQuery($request)->paginate(15);

        $totalPeminjam = Peminjam::count();
        $totalGuru     = Peminjam::where('tipe', 'guru')->count();
        $totalSiswa    = Peminjam::where('tipe', 'siswa')->count();

        return view('peminjam.index', compact('peminjam', 'totalPeminjam', 'totalGuru', 'totalSiswa'));
    }

    public function create()
    {
        $peminjam = Peminjam::orderBy('nama')->get();
        $buku = Buku::where('stok_tersedia', '>', 0)->orderBy('judul')->get();

        return view('peminjam.create', compact('peminjam', 'buku'));
    }

    public function store(Request $request)
    {
        if ($request->has('peminjam_id') && (!is_numeric($request->peminjam_id) || empty($request->peminjam_id))) {
            $request->merge(['peminjam_id' => null]);
        }

        if ($request->filled('buku_ids') && is_array($request->buku_ids) && count($request->buku_ids) > 0 && !$request->filled('buku_id')) {
            $request->merge(['buku_id' => $request->buku_ids[0]]);
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
            'buku_ids'          => 'nullable|array',
            'buku_ids.*'        => 'exists:buku,id',
            'tanggal_pinjam'    => 'nullable|required_if:meminjam_buku,1|date',
            'tanggal_kembali'   => 'nullable|required_if:meminjam_buku,1|date|after_or_equal:tanggal_pinjam',
            'catatan'           => 'nullable|string',
        ]);

        // 1. Simpan atau perbarui data master Peminjam (anggota)
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

        if (!$request->filled('buku_ids') && $request->filled('buku_id')) {
            $request->merge(['buku_ids' => [$request->buku_id]]);
        }
        if ($request->filled('buku_ids') && !$request->filled('buku_id') && is_array($request->buku_ids) && count($request->buku_ids) > 0) {
            $request->merge(['buku_id' => $request->buku_ids[0]]);
        }

        $now = now();
        $bukuIds = $request->input('buku_ids', []);
        if (!empty($validated['buku_id']) && empty($bukuIds)) {
            $bukuIds = [$validated['buku_id']];
        }

        $isPinjam = ($request->filled('meminjam_buku') || !empty($bukuIds)) && !empty($bukuIds);

        // 2. Jika meminjam buku, catat transaksi peminjaman
        $keperluan = $validated['keperluan'] ?? 'Berkunjung & membaca buku di perpustakaan';
        $countBuku = 0;
        if ($isPinjam) {
            $bukuList = Buku::whereIn('id', $bukuIds)->get();
            $stokHabis = [];
            foreach ($bukuList as $b) {
                if ($b->stok_tersedia < 1) {
                    $stokHabis[] = $b->judul;
                }
            }

            if (!empty($stokHabis)) {
                return back()->withErrors(['buku_id' => 'Stok buku berikut sedang habis/tidak tersedia: ' . implode(', ', $stokHabis)])->withInput();
            }

            \Illuminate\Support\Facades\DB::transaction(function () use ($bukuList, $peminjam, $validated, $now) {
                foreach ($bukuList as $buku) {
                    Peminjaman::create([
                        'peminjam_id'    => $peminjam->id,
                        'buku_id'        => $buku->id,
                        'tanggal_pinjam' => $validated['tanggal_pinjam'] ?? $now->toDateString(),
                        'tanggal_kembali'=> $validated['tanggal_kembali'] ?? $now->copy()->addDays(7)->toDateString(),
                        'catatan'        => $validated['catatan'] ?? null,
                        'status'         => 'dipinjam',
                    ]);
                    $buku->decrement('stok_tersedia');
                }
            });

            $countBuku = count($bukuList);
            $keperluan = 'Meminjam ' . $countBuku . ' buku: ' . $bukuList->pluck('judul')->implode(', ');
        }

        // 3. Peminjam juga otomatis dicatat sebagai pengunjung perpustakaan
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
            ? 'Peminjaman ' . ($countBuku > 1 ? $countBuku . ' buku' : 'buku') . ' berhasil dicatat dan kunjungan ' . $peminjam->nama . ' telah direkam!'
            : 'Kunjungan ' . $peminjam->nama . ' berhasil dicatat dan otomatis terdaftar sebagai anggota perpustakaan!';

        $redirectRoute = $request->input('redirect_to') === 'pengunjung' ? 'pengunjung.index' : 'peminjam.index';
        return redirect()->route($redirectRoute)->with('success', $pesan);
    }

    public function show(Peminjam $peminjam)
    {
        $peminjam->load(['peminjaman.buku']);
        $buku = Buku::where('stok_tersedia', '>', 0)->orderBy('judul')->get();
        return view('peminjam.show', compact('peminjam', 'buku'));
    }

    public function edit(Peminjam $peminjam)
    {
        return view('peminjam.edit', compact('peminjam'));
    }

    public function update(Request $request, Peminjam $peminjam)
    {
        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'tipe'          => 'required|in:guru,siswa',
            'kelas_jabatan' => 'nullable|string|max:100',
            'nis_nip'       => 'nullable|string|max:50',
            'telepon'       => 'nullable|string|max:20',
            'alamat'        => 'nullable|string',
        ]);

        $peminjam->update($validated);

        return redirect()->route('peminjam.index')->with('success', 'Data peminjam berhasil diperbarui!');
    }

    public function destroy(Peminjam $peminjam)
    {
        $peminjam->delete();
        return redirect()->route('peminjam.index')->with('success', 'Peminjam berhasil dihapus!');
    }

    /**
     * Unduh file data peminjam ke format Excel (.xls) atau CSV (.csv)
     */
    public function exportExcel(Request $request, PeminjamSpreadsheetService $service)
    {
        $format = $request->get('format', 'xls') === 'csv' ? 'csv' : 'xls';
        $peminjamList = $this->filterPeminjamQuery($request)->get();

        $fileData = $service->exportPeminjam($peminjamList, $format);

        return response($fileData['content'])
            ->header('Content-Type', $fileData['mime'])
            ->header('Content-Disposition', 'attachment; filename="' . $fileData['filename'] . '"')
            ->header('Cache-Control', 'max-age=0');
    }

    /**
     * Tampilkan pratinjau cetak / Export PDF Resmi Data Peminjam
     */
    public function exportPdf(Request $request)
    {
        $peminjamList = $this->filterPeminjamQuery($request)->get();

        $filterInfo = [
            'tipe'    => $request->filled('tipe') && $request->tipe !== 'semua' ? ucfirst($request->tipe) : 'Semua Tipe',
            'search'  => $request->filled('search') ? $request->search : null,
            'tanggal' => Carbon::now()->locale('id')->isoFormat('D MMMM YYYY'),
        ];

        $totalPeminjam     = $peminjamList->count();
        $totalGuru         = $peminjamList->where('tipe', 'guru')->count();
        $totalSiswa        = $peminjamList->where('tipe', 'siswa')->count();
        $totalBukuDipinjam = $peminjamList->sum('buku_dipinjam');

        return view('peminjam.export_pdf', compact('peminjamList', 'filterInfo', 'totalPeminjam', 'totalGuru', 'totalSiswa', 'totalBukuDipinjam'));
    }
}
