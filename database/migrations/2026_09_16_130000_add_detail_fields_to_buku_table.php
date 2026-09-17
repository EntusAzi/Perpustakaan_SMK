<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('buku', function (Blueprint $table) {
            $table->string('id_buku')->nullable()->after('id');
            $table->string('no_inventaris')->nullable()->after('id_buku');
            $table->string('kelas', 50)->nullable()->after('tahun_terbit');
            $table->string('kurikulum', 100)->nullable()->after('kelas');
            $table->string('sumber', 100)->nullable()->after('kurikulum');
            $table->string('keterangan', 255)->nullable()->after('sumber');
            $table->date('tanggal_masuk')->nullable()->after('keterangan');
            $table->string('nomor_rak', 100)->nullable()->after('tanggal_masuk');
        });
    }

    public function down(): void
    {
        Schema::table('buku', function (Blueprint $table) {
            $table->dropColumn([
                'id_buku',
                'no_inventaris',
                'kelas',
                'kurikulum',
                'sumber',
                'keterangan',
                'tanggal_masuk',
                'nomor_rak',
            ]);
        });
    }
};
