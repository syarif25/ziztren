<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenPengajuan extends Model
{
    use HasFactory;

    protected $table = 'dokumen_pengajuans';

    protected $fillable = [
        'pengajuan_id',
        'jenis_dokumen',
        'file_path',
        'file_size',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanBeasiswa::class, 'pengajuan_id');
    }
}
