<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Peminjam;
use App\Models\Peminjaman;
use App\Models\Pengunjung;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name'     => 'Budi Santoso',
            'username' => 'admin_smk',
            'email'    => 'budi@smknusantara.sch.id',
            'password' => Hash::make('password'),
            'role'     => 'pustakawan',
            'jabatan'  => 'Kepala Pustaka',
        ]);

        // ===== BUKU =====
        $bukuData = [
            ['judul' => 'Algoritma & Pemrograman', 'penulis' => 'Prof. Rinaldi Munir', 'penerbit' => 'Informatika', 'tahun_terbit' => 2021, 'kategori' => 'referensi', 'isbn' => '978-602-1152-40-1', 'stok' => 5, 'stok_tersedia' => 4],
            ['judul' => 'Fisika Modern Kelas XI', 'penulis' => 'Kementerian Pendidikan', 'penerbit' => 'Kemdikbud', 'tahun_terbit' => 2022, 'kategori' => 'lks_paket', 'isbn' => '978-602-282-900-2', 'stok' => 30, 'stok_tersedia' => 28],
            ['judul' => 'Laskar Pelangi', 'penulis' => 'Andrea Hirata', 'penerbit' => 'Bentang Pustaka', 'tahun_terbit' => 2005, 'kategori' => 'karya_fiksi', 'isbn' => '978-979-1637-68-8', 'stok' => 3, 'stok_tersedia' => 2],
            ['judul' => 'Panduan Praktis Jaringan Komputer', 'penulis' => 'Agus Wibowo', 'penerbit' => 'Andi Publisher', 'tahun_terbit' => 2020, 'kategori' => 'referensi', 'isbn' => '978-979-29-6400-1', 'stok' => 4, 'stok_tersedia' => 4],
            ['judul' => 'Matematika Kelas XII', 'penulis' => 'Kementerian Pendidikan', 'penerbit' => 'Kemdikbud', 'tahun_terbit' => 2022, 'kategori' => 'lks_paket', 'isbn' => '978-602-282-901-9', 'stok' => 25, 'stok_tersedia' => 22],
            ['judul' => 'Pengantar Akuntansi Masa Kini', 'penulis' => 'Warren Reeve Duchac', 'penerbit' => 'Salemba Empat', 'tahun_terbit' => 2019, 'kategori' => 'referensi', 'isbn' => '978-979-061-520-1', 'stok' => 6, 'stok_tersedia' => 5],
            ['judul' => 'Dasar Jaringan Komputer', 'penulis' => 'Supriyanto', 'penerbit' => 'Yudhistira', 'tahun_terbit' => 2020, 'kategori' => 'referensi', 'isbn' => '978-979-391-750-3', 'stok' => 4, 'stok_tersedia' => 3],
            ['judul' => 'Bahasa Indonesia Kelas X', 'penulis' => 'Kementerian Pendidikan', 'penerbit' => 'Kemdikbud', 'tahun_terbit' => 2021, 'kategori' => 'lks_paket', 'isbn' => '978-602-282-902-6', 'stok' => 30, 'stok_tersedia' => 30],
            ['judul' => 'Bumi Manusia', 'penulis' => 'Pramoedya Ananta Toer', 'penerbit' => 'Lentera Dipantara', 'tahun_terbit' => 2006, 'kategori' => 'karya_fiksi', 'isbn' => '978-979-99738-4-0', 'stok' => 2, 'stok_tersedia' => 1],
            ['judul' => 'Pemrograman Web dengan PHP', 'penulis' => 'Budi Raharjo', 'penerbit' => 'Informatika', 'tahun_terbit' => 2021, 'kategori' => 'referensi', 'isbn' => '978-602-1152-41-8', 'stok' => 3, 'stok_tersedia' => 3],
        ];

        foreach ($bukuData as $buku) {
            Buku::create($buku);
        }

        // ===== PEMINJAM =====
        $peminjamData = [
            ['nama' => 'Ahmad Fauzi', 'tipe' => 'siswa', 'kelas_jabatan' => 'XI RPL 1', 'nis_nip' => '2021001'],
            ['nama' => 'Siti Aminah', 'tipe' => 'siswa', 'kelas_jabatan' => 'XII AKL 2', 'nis_nip' => '2020045'],
            ['nama' => 'Rian Hidayat', 'tipe' => 'siswa', 'kelas_jabatan' => 'X TJKT 3', 'nis_nip' => '2022078'],
            ['nama' => 'Hendra Wijaya', 'tipe' => 'siswa', 'kelas_jabatan' => 'XII TKJ 1', 'nis_nip' => '2020031'],
            ['nama' => 'Dewi Lestari, S.Pd.', 'tipe' => 'guru', 'kelas_jabatan' => 'Staff Pengajar', 'nis_nip' => 'NIP001'],
            ['nama' => 'Nabila Syakila', 'tipe' => 'siswa', 'kelas_jabatan' => 'X DKV 2', 'nis_nip' => '2022102'],
            ['nama' => 'Bambang Prakoso', 'tipe' => 'guru', 'kelas_jabatan' => 'Guru Matematika', 'nis_nip' => 'NIP002'],
            ['nama' => 'Riska Amelia', 'tipe' => 'siswa', 'kelas_jabatan' => 'X RPL 2', 'nis_nip' => '2022089'],
        ];

        foreach ($peminjamData as $p) {
            Peminjam::create($p);
        }

        // ===== PEMINJAMAN =====
        $peminjamanData = [
            ['peminjam_id' => 1, 'buku_id' => 1, 'tanggal_pinjam' => '2024-10-12', 'tanggal_kembali' => '2024-10-26', 'status' => 'dipinjam'],
            ['peminjam_id' => 2, 'buku_id' => 6, 'tanggal_pinjam' => '2024-10-11', 'tanggal_kembali' => '2024-10-25', 'status' => 'kembali', 'tanggal_kembali_aktual' => '2024-10-24'],
            ['peminjam_id' => 3, 'buku_id' => 7, 'tanggal_pinjam' => '2024-10-08', 'tanggal_kembali' => '2024-10-15', 'status' => 'terlambat'],
            ['peminjam_id' => 4, 'buku_id' => 2, 'tanggal_pinjam' => '2024-10-01', 'tanggal_kembali' => '2024-10-15', 'status' => 'dipinjam'],
            ['peminjam_id' => 5, 'buku_id' => 3, 'tanggal_pinjam' => '2024-09-15', 'tanggal_kembali' => '2024-09-29', 'status' => 'kembali', 'tanggal_kembali_aktual' => '2024-09-28'],
            ['peminjam_id' => 5, 'buku_id' => 10, 'tanggal_pinjam' => '2024-10-05', 'tanggal_kembali' => '2024-10-19', 'status' => 'dipinjam'],
            ['peminjam_id' => 8, 'buku_id' => 5, 'tanggal_pinjam' => '2024-10-10', 'tanggal_kembali' => '2024-10-24', 'status' => 'dipinjam'],
        ];

        foreach ($peminjamanData as $p) {
            Peminjaman::create($p);
        }

        // ===== PENGUNJUNG =====
        $today = Carbon::today()->toDateString();
        $pengunjungData = [
            ['nama' => 'Hendra Wijaya', 'tipe' => 'guru', 'kelas_jabatan' => 'Wakil Kepala Sekolah', 'tanggal_kunjungan' => $today, 'waktu_masuk' => '08:15'],
            ['nama' => 'Riska Amelia', 'tipe' => 'siswa', 'kelas_jabatan' => 'X RPL 2', 'tanggal_kunjungan' => $today, 'waktu_masuk' => '09:30'],
            ['nama' => 'Bambang Prakoso', 'tipe' => 'guru', 'kelas_jabatan' => 'Guru B. Indonesia', 'tanggal_kunjungan' => $today, 'waktu_masuk' => '10:05'],
            ['nama' => 'Ahmad Fauzi', 'tipe' => 'siswa', 'kelas_jabatan' => 'XI RPL 1', 'tanggal_kunjungan' => Carbon::yesterday()->toDateString(), 'waktu_masuk' => '09:00'],
            ['nama' => 'Siti Aminah', 'tipe' => 'siswa', 'kelas_jabatan' => 'XII AKL 2', 'tanggal_kunjungan' => Carbon::yesterday()->toDateString(), 'waktu_masuk' => '13:00'],
        ];

        foreach ($pengunjungData as $p) {
            Pengunjung::create($p);
        }
    }
}
