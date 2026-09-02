<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengajuanBeasiswa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pengajuan_beasiswas';

    protected $fillable = [
        'nis',
        'periode_id',
        'lembaga_1_id',
        'kelas_pendidikan_1',
        'lembaga_2_id',
        'kelas_pendidikan_2',
        'lembaga_qiroati_id',
        'kelas_qiroati',
        'jumlah_utap',
        'spp_gasal',
        'spp_genap',
        'living_cost',
        'biaya_lain',
        'tanggal_bantuan',
        'status_pengajuan',
        'prestasi_kamar',
        'isian_bhs',
        'kondisi_ekonomi',
        'prestasi_qiroati',
        'prestasi_madrasah',
        'prestasi_sekolah',
    ];

    public function santri()
    {
        return $this->belongsTo(Santri::class, 'nis', 'nis');
    }

    public function periode()
    {
        return $this->belongsTo(MasterPeriode::class, 'periode_id');
    }

    public function lembaga1()
    {
        return $this->belongsTo(Lembaga::class, 'lembaga_1_id');
    }

    public function lembaga2()
    {
        return $this->belongsTo(Lembaga::class, 'lembaga_2_id');
    }

    public function lembagaQiroati()
    {
        return $this->belongsTo(Lembaga::class, 'lembaga_qiroati_id');
    }

    public function dokumen()
    {
        return $this->hasMany(DokumenPengajuan::class, 'pengajuan_id');
    }

    public function historiStatus()
    {
        return $this->hasMany(HistoriStatusBeasiswa::class, 'pengajuan_id')->orderBy('created_at', 'desc');
    }
}
