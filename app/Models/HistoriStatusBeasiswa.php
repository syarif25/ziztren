<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriStatusBeasiswa extends Model
{
    protected $fillable = [
        'pengajuan_id',
        'status',
        'tanggal_bantuan',
        'catatan'
    ];

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanBeasiswa::class, 'pengajuan_id');
    }
}
