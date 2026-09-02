<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lembaga extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_lembaga',
        'nama_lembaga',
        'bidang',
        'jenis_lembaga',
    ];

    public function pengajuanBeasiswaSebagaiLembaga1()
    {
        return $this->hasMany(PengajuanBeasiswa::class, 'lembaga_1_id');
    }

    public function pengajuanBeasiswaSebagaiLembaga2()
    {
        return $this->hasMany(PengajuanBeasiswa::class, 'lembaga_2_id');
    }
}
