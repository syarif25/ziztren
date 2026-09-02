<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class RegionController extends Controller
{
    public function getProvinsi()
    {
        $provinsi = Province::orderBy('name', 'asc')->get();
        return response()->json($provinsi);
    }

    public function getKota($provinsi_id)
    {
        $kota = City::where('province_id', $provinsi_id)->orderBy('name', 'asc')->get();
        return response()->json($kota);
    }

    public function getKecamatan($kota_id)
    {
        $kecamatan = District::where('city_id', $kota_id)->orderBy('name', 'asc')->get();
        return response()->json($kecamatan);
    }

    public function getDesa($kecamatan_id)
    {
        $desa = Village::where('district_id', $kecamatan_id)->orderBy('name', 'asc')->get();
        return response()->json($desa);
    }
}
