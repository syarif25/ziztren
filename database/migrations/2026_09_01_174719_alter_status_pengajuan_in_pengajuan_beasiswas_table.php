<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_beasiswas', function (Blueprint $table) {
            $table->string('status_pengajuan', 50)->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_beasiswas', function (Blueprint $table) {
            $table->enum('status_pengajuan', ['pending', 'disetujui', 'ditolak'])->default('pending')->change();
        });
    }
};
