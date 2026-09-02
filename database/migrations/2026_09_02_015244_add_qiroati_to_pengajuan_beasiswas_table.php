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
            $table->unsignedBigInteger('lembaga_qiroati_id')->nullable()->after('lembaga_2_id');
            $table->string('kelas_qiroati')->nullable()->after('lembaga_qiroati_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_beasiswas', function (Blueprint $table) {
            $table->dropColumn(['lembaga_qiroati_id', 'kelas_qiroati']);
        });
    }
};
