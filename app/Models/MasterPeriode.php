<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPeriode extends Model
{
    use HasFactory;

    protected $fillable = [
        'tahun_masehi',
        'gelombang',
        'tgl_buka',
        'tgl_tutup',
        'status',
        'kuota',
    ];

    public function pengajuanBeasiswas()
    {
        return $this->hasMany(PengajuanBeasiswa::class, 'periode_id');
    }

    public function getNamaPeriodeAttribute()
    {
        return 'Tahun ' . $this->tahun_masehi . ' - Gel. ' . $this->gelombang;
    }

    public function getIsActiveAttribute()
    {
        return strtolower($this->status) === 'aktif';
    }
}
