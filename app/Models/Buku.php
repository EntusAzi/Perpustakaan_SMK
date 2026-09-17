<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';

    protected $fillable = [
        'id_buku', 'no_inventaris', 'judul', 'penulis', 'penerbit',
        'tahun_terbit', 'kelas', 'kurikulum', 'sumber', 'keterangan',
        'tanggal_masuk', 'nomor_rak', 'kategori', 'isbn', 'stok',
        'stok_tersedia', 'cover_image', 'deskripsi',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'tahun_terbit'  => 'integer',
        'stok'          => 'integer',
        'stok_tersedia' => 'integer',
    ];

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function getKategoriLabelAttribute(): string
    {
        return match($this->kategori) {
            'lks_paket'   => 'LKS/Paket',
            'referensi'   => 'Referensi',
            'karya_fiksi' => 'Karya Fiksi',
            default       => 'Umum',
        };
    }

    public function getStatusAttribute(): string
    {
        return $this->stok_tersedia > 0 ? 'tersedia' : 'dipinjam';
    }
}
