<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengunjung', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('tipe', ['guru', 'siswa'])->default('siswa');
            $table->string('kelas_jabatan')->nullable();
            $table->string('nis_nip')->nullable();
            $table->date('tanggal_kunjungan');
            $table->time('waktu_masuk');
            $table->time('waktu_keluar')->nullable();
            $table->text('keperluan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengunjung');
    }
};
