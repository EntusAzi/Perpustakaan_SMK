<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjam;
use App\Models\Peminjaman;
use App\Models\Pengunjung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    /**
     * Tampilkan Halaman Profil Pengguna
     */
    public function index()
    {
        $user = Auth::user();

        // Statistik ringkas perpustakaan yang dikelola
        $totalBuku            = Buku::count();
        $totalPeminjam        = Peminjam::count();
        $totalKunjungan       = Pengunjung::count();
        $totalPeminjamanAktif = Peminjaman::where('status', 'dipinjam')->count();

        return view('akun.profil', compact(
            'user',
            'totalBuku',
            'totalPeminjam',
            'totalKunjungan',
            'totalPeminjamanAktif'
        ));
    }

    /**
     * Tampilkan Halaman Edit Profil
     */
    public function edit()
    {
        $user = Auth::user();

        return view('akun.edit_profil', compact('user'));
    }

    /**
     * Proses Simpan Perubahan Profil Pengguna
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'username'         => 'required|string|max:100|alpha_dash|unique:users,username,' . $user->id,
            'email'            => 'required|email|max:255|unique:users,email,' . $user->id,
            'jabatan'          => 'nullable|string|max:100',
            'avatar'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'remove_avatar'    => 'nullable|boolean',
            'current_password' => 'nullable|required_with:password|string',
            'password'         => 'nullable|string|min:6|confirmed',
        ], [
            'name.required'             => 'Nama lengkap wajib diisi.',
            'username.required'         => 'Username wajib diisi.',
            'username.unique'           => 'Username ini sudah digunakan.',
            'username.alpha_dash'       => 'Username hanya boleh huruf, angka, tanda strip, dan garis bawah.',
            'email.required'            => 'Alamat email wajib diisi.',
            'email.email'               => 'Format email tidak valid.',
            'email.unique'              => 'Email ini sudah terdaftar di sistem.',
            'avatar.image'              => 'File avatar harus berupa gambar.',
            'avatar.max'                => 'Ukuran foto maksimal 2MB.',
            'current_password.required_with' => 'Masukkan password saat ini untuk mengganti password.',
            'password.min'              => 'Password baru minimal 6 karakter.',
            'password.confirmed'        => 'Konfirmasi password baru tidak cocok.',
        ]);

        // Verifikasi password saat ini jika ada input ganti password
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors([
                    'current_password' => 'Password saat ini salah. Periksa kembali password Anda.',
                ])->withInput();
            }

            $user->password = Hash::make($request->password);
        }

        // Handle upload avatar baru
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        } elseif ($request->boolean('remove_avatar')) {
            // Hapus avatar jika dicentang hapus
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = null;
        }

        // Update identitas profil
        $user->name     = $validated['name'];
        $user->username = $validated['username'];
        $user->email    = $validated['email'];
        $user->jabatan  = $validated['jabatan'] ?? $user->jabatan;

        $user->save();

        return redirect()->route('profil.index')->with('success', 'Profil akun Anda berhasil diperbarui!');
    }
}
