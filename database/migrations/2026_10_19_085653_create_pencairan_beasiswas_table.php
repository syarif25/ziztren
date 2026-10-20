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
        Schema::create('pencairan_beasiswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan_beasiswas')->onDelete('cascade');
            $table->string('kategori_bantuan'); // utap, spp_gasal, spp_genap, living_cost, biaya_lain
            $table->decimal('nominal', 15, 2);
            $table->string('metode_pencairan'); // transfer, tunai
            $table->date('tanggal_cair');
            $table->string('tahap_ke')->nullable(); // Tahap 1, Bulan 1, Pelunasan, dll.
            $table->string('nomor_referensi')->nullable(); // No transaksi/struk/kuitansi
            $table->string('nama_penerima')->nullable(); // Nama santri / wali yang menerima
            $table->string('file_bukti')->nullable(); // Path foto/pdf kuitansi/bukti transfer
            $table->text('catatan')->nullable(); // Catatan tambahan petugas
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pencairan_beasiswas');
    }
};
