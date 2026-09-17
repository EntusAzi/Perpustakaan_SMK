<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'peminjam_id', 'buku_id', 'tanggal_pinjam',
        'tanggal_kembali', 'tanggal_kembali_aktual', 'status', 'catatan',
    ];

    protected $casts = [
        'tanggal_pinjam'        => 'date',
        'tanggal_kembali'       => 'date',
        'tanggal_kembali_aktual'=> 'date',
    ];

    public function peminjam()
    {
        return $this->belongsTo(Peminjam::class);
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'dipinjam'  => 'Dipinjam',
            'kembali'   => 'Kembali',
            'terlambat' => 'Terlambat',
            default     => 'Dipinjam',
        };
    }
}
