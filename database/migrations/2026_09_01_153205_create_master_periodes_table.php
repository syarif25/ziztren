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
        Schema::create('master_periodes', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun_masehi');
            $table->integer('gelombang');
            $table->date('tgl_buka');
            $table->date('tgl_tutup');
            $table->enum('status', ['aktif', 'tutup'])->default('tutup');
            $table->integer('kuota');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_periodes');
    }
};
