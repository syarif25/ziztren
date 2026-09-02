<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengajuan_beasiswas', function (Blueprint $table) {
            $table->text('prestasi_kamar')->nullable();
            $table->text('isian_bhs')->nullable();
            $table->text('kondisi_ekonomi')->nullable();
            $table->text('prestasi_qiroati')->nullable();
            $table->text('prestasi_madrasah')->nullable();
            $table->text('prestasi_sekolah')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_beasiswas', function (Blueprint $table) {
            $table->dropColumn([
                'prestasi_kamar',
                'isian_bhs',
                'kondisi_ekonomi',
                'prestasi_qiroati',
                'prestasi_madrasah',
                'prestasi_sekolah'
            ]);
        });
    }
};
