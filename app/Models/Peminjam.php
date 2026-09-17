<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjam extends Model
{
    use HasFactory;

    protected $table = 'peminjam';

    protected $fillable = [
        'nama', 'tipe', 'kelas_jabatan', 'nis_nip', 'telepon', 'alamat',
    ];

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function peminjamanAktif()
    {
        return $this->hasMany(Peminjaman::class)->where('status', 'dipinjam');
    }

    public function getJumlahBukuDipinjamAttribute(): int
    {
        return $this->peminjamanAktif()->count();
    }

    public function getKeteranganAttribute(): string
    {
        $aktif = $this->peminjaman()->where('status', 'dipinjam')->count();
        $total = $this->peminjaman()->count();

        if ($total === 0) return 'selesai';
        if ($aktif === 0) return 'selesai';
        if ($aktif === $total) return 'belum';
        return 'sebagian';
    }
}
