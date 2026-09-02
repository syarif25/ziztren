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
        Schema::table('santris', function (Blueprint $table) {
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('kamar', 50)->nullable();
            $table->string('nama_wali', 150)->nullable();
            $table->string('pekerjaan_wali', 100)->nullable();
            
            $table->foreignId('provinsi_id')->nullable()->constrained('indonesia_provinces')->nullOnDelete();
            $table->foreignId('kabupaten_id')->nullable()->constrained('indonesia_cities')->nullOnDelete();
            $table->foreignId('kecamatan_id')->nullable()->constrained('indonesia_districts')->nullOnDelete();
            $table->foreignId('desa_id')->nullable()->constrained('indonesia_villages')->nullOnDelete();
            
            $table->text('alamat_lengkap')->nullable();
            $table->string('no_hp_wali', 20)->nullable();
            $table->string('foto_santri_path')->nullable();
            
            $table->text('keterangan_ekonomi')->nullable();
            $table->text('keterangan_bhs')->nullable();
            $table->text('prestasi_kamar')->nullable();
            $table->text('prestasi_non_akademik')->nullable();
            $table->text('prestasi_madrasah')->nullable();
            $table->text('prestasi_sekolah')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('santris', function (Blueprint $table) {
            $table->dropForeign(['provinsi_id']);
            $table->dropForeign(['kabupaten_id']);
            $table->dropForeign(['kecamatan_id']);
            $table->dropForeign(['desa_id']);
            
            $table->dropColumn([
                'jenis_kelamin',
                'tempat_lahir',
                'tanggal_lahir',
                'kamar',
                'nama_wali',
                'pekerjaan_wali',
                'provinsi_id',
                'kabupaten_id',
                'kecamatan_id',
                'desa_id',
                'alamat_lengkap',
                'no_hp_wali',
                'foto_santri_path',
                'keterangan_ekonomi',
                'keterangan_bhs',
                'prestasi_kamar',
                'prestasi_non_akademik',
                'prestasi_madrasah',
                'prestasi_sekolah'
            ]);
        });
    }
};
