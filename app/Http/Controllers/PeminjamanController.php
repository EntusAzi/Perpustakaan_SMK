<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjam;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['peminjam', 'buku']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->whereHas('peminjam', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%');
            });
        }

        $peminjaman = $query->latest()->paginate(15);

        return view('peminjaman.index', compact('peminjaman'));
    }

    public function create(Request $request)
    {
        $peminjam = Peminjam::orderBy('nama')->get();
        $buku = Buku::where('stok_tersedia', '>', 0)->orderBy('judul')->get();

        $selectedPeminjam = null;
        if ($request->filled('peminjam_id')) {
            $selectedPeminjam = Peminjam::find($request->peminjam_id);
        }

        return view('peminjaman.create', compact('peminjam', 'buku', 'selectedPeminjam'));
    }

    public function store(Request $request)
    {
        // Normalisasi: jika buku_ids tidak dikirim tapi buku_id ada
        if (!$request->filled('buku_ids') && $request->filled('buku_id')) {
            $request->merge(['buku_ids' => [$request->buku_id]]);
        }

        $validated = $request->validate([
            'peminjam_id'    => 'required|exists:peminjam,id',
            'buku_ids'       => 'required|array|min:1',
            'buku_ids.*'     => 'required|exists:buku,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali'=> 'required|date|after_or_equal:tanggal_pinjam',
            'catatan'        => 'nullable|string',
        ], [
            'buku_ids.required' => 'Silakan pilih minimal 1 buku yang akan dipinjam.',
            'buku_ids.min'      => 'Silakan pilih minimal 1 buku yang akan dipinjam.',
            'tanggal_kembali.after_or_equal' => 'Tanggal kembali harus sama atau setelah tanggal pinjam.',
        ]);

        $bukuList = Buku::whereIn('id', $validated['buku_ids'])->get();

        // Validasi ketersediaan stok tiap buku
        $stokHabis = [];
        foreach ($bukuList as $b) {
            if ($b->stok_tersedia < 1) {
                $stokHabis[] = $b->judul;
            }
        }

        if (!empty($stokHabis)) {
            return back()->withErrors([
                'buku_ids' => 'Stok buku berikut sedang habis/tidak tersedia: ' . implode(', ', $stokHabis)
            ])->withInput();
        }

        // Simpan setiap peminjaman buku dalam satu transaksi
        \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $bukuList) {
            foreach ($bukuList as $b) {
                Peminjaman::create([
                    'peminjam_id'     => $validated['peminjam_id'],
                    'buku_id'         => $b->id,
                    'tanggal_pinjam'  => $validated['tanggal_pinjam'],
                    'tanggal_kembali' => $validated['tanggal_kembali'],
                    'status'          => 'dipinjam',
                    'catatan'         => $validated['catatan'] ?? null,
                ]);
                $b->decrement('stok_tersedia');
            }
        });

        $count = count($bukuList);
        $successMsg = $count > 1 
            ? "Berhasil mencatat peminjaman {$count} buku sekaligus!" 
            : "Peminjaman buku berhasil dicatat!";

        if ($request->filled('from_peminjam')) {
            return redirect()->route('peminjam.show', $validated['peminjam_id'])
                             ->with('success', $successMsg);
        }

        return redirect()->route('peminjaman.index')->with('success', $successMsg);
    }

    public function kembalikan(Peminjaman $peminjaman)
    {
        $status = Carbon::today()->gt($peminjaman->tanggal_kembali) ? 'terlambat' : 'kembali';

        $peminjaman->update([
            'status'                 => $status,
            'tanggal_kembali_aktual' => Carbon::today(),
        ]);

        $peminjaman->buku->increment('stok_tersedia');

        return redirect()->back()->with('success', 'Buku berhasil dikembalikan!');
    }
}
