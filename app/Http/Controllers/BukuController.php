<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Services\BookSpreadsheetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::query();

        if ($request->filled('kategori') && $request->kategori !== 'semua') {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('kelas') && $request->kelas !== 'semua') {
            $query->where('kelas', $request->kelas);
        }

        if ($request->filled('status')) {
            if ($request->status === 'tersedia') {
                $query->where('stok_tersedia', '>', 0);
            } elseif ($request->status === 'dipinjam') {
                $query->where('stok_tersedia', 0);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%")
                  ->orWhere('penerbit', 'like', "%{$search}%")
                  ->orWhere('id_buku', 'like', "%{$search}%")
                  ->orWhere('no_inventaris', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhere('nomor_rak', 'like', "%{$search}%")
                  ->orWhere('kurikulum', 'like', "%{$search}%")
                  ->orWhere('sumber', 'like', "%{$search}%");
            });
        }

        $buku = $query->latest()->paginate(12);

        return view('buku.index', compact('buku'));
    }

    public function create()
    {
        $lastId = Buku::max('id') ?? 0;
        $suggestedIdBuku = 'BK-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
        $today = date('Y-m-d');

        return view('buku.tambah_buku', compact('suggestedIdBuku', 'today'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_buku'       => 'required|string|max:100',
            'no_inventaris' => 'required|string|max:100',
            'tanggal_masuk' => 'required|date',
            'nomor_rak'     => 'required|string|max:100',
            'judul'         => 'required|string|max:255',
            'penulis'       => 'required|string|max:255',
            'penerbit'      => 'required|string|max:255',
            'tahun_terbit'  => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'stok'          => 'required|integer|min:1',
            'kelas'         => 'required|string|max:50',
            'kurikulum'     => 'required|string|max:100',
            'sumber'        => 'required|string|max:100',
            'keterangan'    => 'required|string|max:255',
            'kategori'      => 'required|in:lks_paket,referensi,karya_fiksi,umum',
            'isbn'          => 'nullable|string|max:50',
            'cover_image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'deskripsi'     => 'nullable|string',
        ], [
            'id_buku.required'       => 'ID Buku wajib diisi.',
            'no_inventaris.required' => 'Nomor Inventaris wajib diisi.',
            'tanggal_masuk.required' => 'Tanggal masuk ke perpustakaan wajib diisi.',
            'nomor_rak.required'     => 'Nomor rak / lemari wajib diisi.',
            'judul.required'         => 'Judul buku wajib diisi.',
            'penulis.required'       => 'Pengarang / penulis wajib diisi.',
            'penerbit.required'      => 'Penerbit wajib diisi.',
            'tahun_terbit.required'  => 'Tahun terbit wajib diisi.',
            'stok.required'          => 'Jumlah buku / stok wajib diisi.',
            'kelas.required'         => 'Peruntukan kelas wajib dipilih.',
            'kurikulum.required'     => 'Kurikulum wajib diisi.',
            'sumber.required'        => 'Sumber buku wajib diisi.',
            'keterangan.required'    => 'Keterangan mata pelajaran/keahlian wajib diisi.',
            'kategori.required'      => 'Kategori buku wajib dipilih.',
        ]);

        $validated['stok_tersedia'] = $validated['stok'];

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        Buku::create($validated);

        return redirect()->route('buku.index')->with('success', 'Buku baru berhasil ditambahkan!');
    }

    public function show(Buku $buku)
    {
        $buku->load(['peminjaman.peminjam']);
        return view('buku.show', compact('buku'));
    }

    public function edit(Buku $buku)
    {
        return view('buku.edit_buku', compact('buku'));
    }

    public function update(Request $request, Buku $buku)
    {
        $validated = $request->validate([
            'id_buku'       => 'required|string|max:100',
            'no_inventaris' => 'required|string|max:100',
            'tanggal_masuk' => 'required|date',
            'nomor_rak'     => 'required|string|max:100',
            'judul'         => 'required|string|max:255',
            'penulis'       => 'required|string|max:255',
            'penerbit'      => 'required|string|max:255',
            'tahun_terbit'  => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'stok'          => 'required|integer|min:1',
            'kelas'         => 'required|string|max:50',
            'kurikulum'     => 'required|string|max:100',
            'sumber'        => 'required|string|max:100',
            'keterangan'    => 'required|string|max:255',
            'kategori'      => 'required|in:lks_paket,referensi,karya_fiksi,umum',
            'isbn'          => 'nullable|string|max:50',
            'cover_image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'deskripsi'     => 'nullable|string',
        ], [
            'id_buku.required'       => 'ID Buku wajib diisi.',
            'no_inventaris.required' => 'Nomor Inventaris wajib diisi.',
            'tanggal_masuk.required' => 'Tanggal masuk ke perpustakaan wajib diisi.',
            'nomor_rak.required'     => 'Nomor rak / lemari wajib diisi.',
            'judul.required'         => 'Judul buku wajib diisi.',
            'penulis.required'       => 'Pengarang / penulis wajib diisi.',
            'penerbit.required'      => 'Penerbit wajib diisi.',
            'tahun_terbit.required'  => 'Tahun terbit wajib diisi.',
            'stok.required'          => 'Jumlah buku / stok wajib diisi.',
            'kelas.required'         => 'Peruntukan kelas wajib dipilih.',
            'kurikulum.required'     => 'Kurikulum wajib diisi.',
            'sumber.required'        => 'Sumber buku wajib diisi.',
            'keterangan.required'    => 'Keterangan mata pelajaran/keahlian wajib diisi.',
            'kategori.required'      => 'Kategori buku wajib dipilih.',
        ]);

        if (empty($validated['id_buku'])) {
            $validated['id_buku'] = $buku->id_buku ?: ('BK-' . str_pad($buku->id, 4, '0', STR_PAD_LEFT));
        }

        if (empty($validated['kategori'])) {
            $validated['kategori'] = $buku->kategori ?: 'umum';
        }

        // Sesuaikan stok tersedia jika total stok diubah
        $dipinjam = max(0, $buku->stok - $buku->stok_tersedia);
        $validated['stok_tersedia'] = max(0, $validated['stok'] - $dipinjam);

        if ($request->hasFile('cover_image')) {
            if ($buku->cover_image) {
                Storage::disk('public')->delete($buku->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $buku->update($validated);

        return redirect()->route('buku.index')->with('success', 'Data buku berhasil diperbarui!');
    }

    public function destroy(Buku $buku)
    {
        if ($buku->cover_image) {
            Storage::disk('public')->delete($buku->cover_image);
        }
        $buku->delete();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus!');
    }

    /**
     * Helper filter query untuk buku
     */
    protected function filterBukuQuery(Request $request)
    {
        $query = Buku::query();

        if ($request->filled('kategori') && $request->kategori !== 'semua') {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('kelas') && $request->kelas !== 'semua') {
            $query->where('kelas', $request->kelas);
        }

        if ($request->filled('status')) {
            if ($request->status === 'tersedia') {
                $query->where('stok_tersedia', '>', 0);
            } elseif ($request->status === 'dipinjam') {
                $query->where('stok_tersedia', 0);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%")
                  ->orWhere('penerbit', 'like', "%{$search}%")
                  ->orWhere('id_buku', 'like', "%{$search}%")
                  ->orWhere('no_inventaris', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhere('nomor_rak', 'like', "%{$search}%")
                  ->orWhere('kurikulum', 'like', "%{$search}%")
                  ->orWhere('sumber', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    /**
     * Tampilkan halaman Export & Import Buku
     */
    public function exportImport(Request $request)
    {
        $totalBuku      = Buku::count();
        $totalEksemplar = Buku::sum('stok') ?: 0;
        $totalTersedia  = Buku::sum('stok_tersedia') ?: 0;
        $totalDipinjam  = max(0, $totalEksemplar - $totalTersedia);

        $kategoriCounts = [
            'semua'       => $totalBuku,
            'lks_paket'   => Buku::where('kategori', 'lks_paket')->count(),
            'referensi'   => Buku::where('kategori', 'referensi')->count(),
            'karya_fiksi' => Buku::where('kategori', 'karya_fiksi')->count(),
            'umum'        => Buku::where('kategori', 'umum')->count(),
        ];

        $kelasCounts = [
            'X'     => Buku::where('kelas', 'X')->count(),
            'XI'    => Buku::where('kelas', 'XI')->count(),
            'XII'   => Buku::where('kelas', 'XII')->count(),
            'Semua' => Buku::where('kelas', 'Semua')->count(),
        ];

        return view('buku.export_import', compact(
            'totalBuku',
            'totalEksemplar',
            'totalTersedia',
            'totalDipinjam',
            'kategoriCounts',
            'kelasCounts'
        ));
    }

    /**
     * Unduh file data buku ke format Excel (.xls) atau CSV (.csv)
     */
    public function exportExcel(Request $request, BookSpreadsheetService $service)
    {
        $format = $request->get('format', 'xls') === 'csv' ? 'csv' : 'xls';
        $query  = $this->filterBukuQuery($request);
        $books  = $query->orderBy('id_buku', 'asc')->get();

        $fileData = $service->exportBooks($books, $format);

        return response($fileData['content'])
            ->header('Content-Type', $fileData['mime'])
            ->header('Content-Disposition', 'attachment; filename="' . $fileData['filename'] . '"')
            ->header('Cache-Control', 'max-age=0');
    }

    /**
     * Tampilkan halaman pratinjau cetak / Export PDF Resmi (A4 Landscape)
     */
    public function exportPdf(Request $request)
    {
        $query = $this->filterBukuQuery($request);
        $books = $query->orderBy('id_buku', 'asc')->get();

        $filterInfo = [
            'kategori' => $request->filled('kategori') && $request->kategori !== 'semua'
                ? match($request->kategori) {
                    'lks_paket'   => 'LKS / Paket',
                    'referensi'   => 'Referensi',
                    'karya_fiksi' => 'Karya Fiksi',
                    default       => ucfirst($request->kategori),
                }
                : 'Semua Kategori',
            'kelas'    => $request->filled('kelas') && $request->kelas !== 'semua'
                ? 'Kelas ' . $request->kelas
                : 'Semua Kelas',
            'status'   => $request->filled('status')
                ? ucfirst($request->status)
                : 'Semua Status',
            'tanggal'  => date('d F Y'),
        ];

        $totalEksemplar = $books->sum('stok');
        $totalTersedia  = $books->sum('stok_tersedia');

        return view('buku.export_pdf', compact('books', 'filterInfo', 'totalEksemplar', 'totalTersedia'));
    }

    /**
     * Unduh template file Excel (.xls) atau CSV (.csv) untuk import data buku
     */
    public function downloadTemplate(Request $request, BookSpreadsheetService $service)
    {
        $format = $request->get('format', 'xls') === 'csv' ? 'csv' : 'xls';
        $template = $service->getTemplateContent($format);

        return response($template['content'])
            ->header('Content-Type', $template['mime'])
            ->header('Content-Disposition', 'attachment; filename="' . $template['filename'] . '"')
            ->header('Cache-Control', 'max-age=0');
    }

    /**
     * Proses import file Excel / CSV ke database buku
     */
    public function importExcel(Request $request, BookSpreadsheetService $service)
    {
        $request->validate([
            'file_excel'     => 'required|file|max:10240',
            'duplicate_mode' => 'nullable|in:skip,update',
        ], [
            'file_excel.required' => 'Silakan pilih berkas Excel atau CSV yang ingin diimpor.',
            'file_excel.file'     => 'Berkas yang diunggah tidak valid.',
            'file_excel.max'      => 'Ukuran berkas maksimal adalah 10 MB.',
        ]);

        $file = $request->file('file_excel');
        $ext  = strtolower($file->getClientOriginalExtension());

        if (!in_array($ext, ['xlsx', 'xls', 'csv', 'txt'])) {
            return back()->with('error', 'Format berkas tidak didukung. Harap unggah file berformat .xlsx, .xls, atau .csv.');
        }

        $duplicateMode = $request->input('duplicate_mode', 'skip');

        try {
            $rawRows = $service->parseUploadedFile($file->getRealPath(), $ext);
            $result  = $service->processRows($rawRows, $duplicateMode);

            if ($result['imported'] === 0 && $result['updated'] === 0 && $result['skipped'] === 0) {
                return back()->with('error', 'Tidak ada data buku yang dapat diproses dari berkas yang diunggah.');
            }

            $msg = "Import data selesai! Berhasil menambahkan {$result['imported']} buku baru.";
            if ($result['updated'] > 0) {
                $msg .= " Sebanyak {$result['updated']} data buku diperbarui.";
            }
            if ($result['skipped'] > 0) {
                $msg .= " Sebanyak {$result['skipped']} baris dilewati (duplikat atau data wajib tidak lengkap).";
            }

            return redirect()->route('buku.export_import')
                ->with('import_success', true)
                ->with('success', $msg)
                ->with('import_errors', $result['errors']);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses file: ' . $e->getMessage());
        }
    }
}
