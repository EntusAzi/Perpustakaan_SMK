<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengunjung extends Model
{
    use HasFactory;

    protected $table = 'pengunjung';

    protected $fillable = [
        'nama', 'tipe', 'kelas_jabatan', 'nis_nip',
        'tanggal_kunjungan', 'waktu_masuk', 'waktu_keluar', 'keperluan',
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
    ];
}
