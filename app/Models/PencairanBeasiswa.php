<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PencairanBeasiswa extends Model
{
    use HasFactory;

    protected $table = 'pencairan_beasiswas';

    protected $fillable = [
        'pengajuan_id',
        'kategori_bantuan',
        'nominal',
        'metode_pencairan',
        'tanggal_cair',
        'tahap_ke',
        'nomor_referensi',
        'nama_penerima',
        'file_bukti',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'tanggal_cair' => 'date',
        'nominal' => 'decimal:2',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanBeasiswa::class, 'pengajuan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getKategoriLabelAttribute()
    {
        return match ($this->kategori_bantuan) {
            'utap' => 'UTAP (Pondok)',
            'spp_gasal' => 'SPP Gasal',
            'spp_genap' => 'SPP Genap',
            'living_cost' => 'Living Cost (Uang Saku/Makan)',
            'biaya_lain' => 'Biaya Lain-lain',
            default => ucfirst(str_replace('_', ' ', $this->kategori_bantuan)),
        };
    }
}
