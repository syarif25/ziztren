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
        Schema::create('pengajuan_beasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('nis', 50); // FK to santris
            $table->string('tahun_pelajaran');
            $table->string('gelombang'); // Can be integer, using string to be safe for "Gelombang 1" etc
            
            $table->unsignedBigInteger('lembaga_1_id'); // FK to lembagas
            $table->string('kelas_pendidikan_1');
            
            $table->unsignedBigInteger('lembaga_2_id')->nullable(); // FK to lembagas
            $table->string('kelas_pendidikan_2')->nullable();
            
            $table->decimal('jumlah_utap', 15, 2)->default(0);
            $table->decimal('spp_gasal', 15, 2)->default(0);
            $table->decimal('spp_genap', 15, 2)->default(0);
            $table->decimal('living_cost', 15, 2)->default(0);
            $table->decimal('biaya_lain', 15, 2)->default(0);
            
            $table->date('tanggal_bantuan')->nullable();
            $table->enum('status_pengajuan', ['pending', 'disetujui', 'ditolak'])->default('pending');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign key constraints
            // $table->foreign('nis')->references('nis')->on('santris')->onDelete('cascade');
            // Assuming lembagas has id
            // $table->foreign('lembaga_1_id')->references('id')->on('lembagas')->onDelete('cascade');
            // $table->foreign('lembaga_2_id')->references('id')->on('lembagas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_beasiswas');
    }
};
