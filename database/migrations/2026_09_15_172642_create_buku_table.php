<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('penulis');
            $table->string('penerbit')->nullable();
            $table->year('tahun_terbit')->nullable();
            $table->enum('kategori', ['lks_paket', 'referensi', 'karya_fiksi', 'umum'])->default('umum');
            $table->string('isbn')->nullable()->unique();
            $table->integer('stok')->default(1);
            $table->integer('stok_tersedia')->default(1);
            $table->string('cover_image')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};
