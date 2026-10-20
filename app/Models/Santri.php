<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    use HasFactory;

    // Table name is explicitly defined though optional since Laravel uses pluralization
    protected $table = 'santris';

    // Specify the primary key since it is not 'id'
    protected $primaryKey = 'nis';

    // Important: 'nis' is a string, not an auto-incrementing integer
    public $incrementing = false;
    protected $keyType = 'string';

    // Mass assignable attributes
    protected $fillable = [
        'nis',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'anak_ke',
        'jumlah_saudara',
        'kamar',
        'nama_wali',
        'pekerjaan_wali',
        'provinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'desa_id',
        'alamat_lengkap',
        'no_hp_wali',
        'foto_santri_path',
        'keterangan_ekonomi',
        'keterangan_bhs',
        'prestasi_kamar',
        'prestasi_non_akademik',
        'prestasi_madrasah',
        'prestasi_sekolah',
    ];

    public function pengajuanBeasiswa()
    {
        return $this->hasMany(PengajuanBeasiswa::class, 'nis', 'nis');
    }

    public function provinsi()
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\Province::class, 'provinsi_id');
    }

    public function kabupaten()
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\City::class, 'kabupaten_id');
    }

    public function kecamatan()
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\District::class, 'kecamatan_id');
    }

    public function desa()
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\Village::class, 'desa_id');
    }

    /**
     * Scope query santri berdasarkan gender role operator (Putra / Putri)
     */
    public function scopeForUser($query, $user = null)
    {
        $user = $user ?: auth()->user();
        if (!$user) return $query;

        $genderScope = $user->getGenderScope();
        if ($genderScope) {
            return $query->where('jenis_kelamin', $genderScope);
        }

        return $query;
    }
}
