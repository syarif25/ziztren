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
        'tanda_tangan',
        'tanda_tangan_wali',
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

    public function pencairans()
    {
        return $this->hasMany(PencairanBeasiswa::class, 'pengajuan_id')->orderBy('tanggal_cair', 'desc')->orderBy('id', 'desc');
    }

    public function getTotalBantuanAttribute()
    {
        return (float) ($this->jumlah_utap ?? 0)
            + (float) ($this->spp_gasal ?? 0)
            + (float) ($this->spp_genap ?? 0)
            + (float) ($this->living_cost ?? 0)
            + (float) ($this->biaya_lain ?? 0);
    }

    public function getTotalDicairkanAttribute()
    {
        return (float) $this->pencairans()->sum('nominal');
    }

    public function getSisaPencairanAttribute()
    {
        return max(0, $this->total_bantuan - $this->total_dicairkan);
    }

    public function getProgressPencairanAttribute()
    {
        $total = $this->total_bantuan;
        if ($total <= 0) return 0;
        return min(100, round(($this->total_dicairkan / $total) * 100));
    }

    /**
     * Scope query otomatis berdasarkan gender role operator (Putra / Putri)
     */
    public function scopeForUser($query, $user = null)
    {
        $user = $user ?: auth()->user();
        if (!$user) return $query;

        $genderScope = $user->getGenderScope();
        if ($genderScope) {
            return $query->whereHas('santri', function ($q) use ($genderScope) {
                $q->where('jenis_kelamin', $genderScope);
            });
        }

        return $query;
    }
}
