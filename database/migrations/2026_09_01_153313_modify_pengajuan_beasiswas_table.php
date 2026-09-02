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
            $table->dropColumn(['tahun_pelajaran', 'gelombang']);
            $table->foreignId('periode_id')->nullable()->constrained('master_periodes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_beasiswas', function (Blueprint $table) {
            $table->dropForeign(['periode_id']);
            $table->dropColumn('periode_id');
            $table->string('tahun_pelajaran')->nullable();
            $table->string('gelombang')->nullable();
        });
    }
};
