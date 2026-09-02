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
        Schema::create('dokumen_pengajuans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengajuan_id'); // FK to pengajuan_beasiswas
            $table->string('jenis_dokumen', 50); // e.g., 'KTP', 'KK', 'Raport'
            $table->string('file_path');
            $table->integer('file_size'); // in bytes
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('pengajuan_id')->references('id')->on('pengajuan_beasiswas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_pengajuans');
    }
};
