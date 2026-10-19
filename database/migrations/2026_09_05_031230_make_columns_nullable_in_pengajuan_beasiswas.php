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
            $table->unsignedBigInteger('lembaga_1_id')->nullable()->change();
            $table->string('kelas_pendidikan_1')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_beasiswas', function (Blueprint $table) {
            $table->unsignedBigInteger('lembaga_1_id')->nullable(false)->change();
            $table->string('kelas_pendidikan_1')->nullable(false)->change();
        });
    }
};
