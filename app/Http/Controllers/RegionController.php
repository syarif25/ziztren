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
        $province = Province::find($provinsi_id);
        $code = $province ? $province->code : $provinsi_id;
        $kota = City::where('province_code', $code)->orderBy('name', 'asc')->get();
        return response()->json($kota);
    }

    public function getKecamatan($kota_id)
    {
        $city = City::find($kota_id);
        $code = $city ? $city->code : $kota_id;
        $kecamatan = District::where('city_code', $code)->orderBy('name', 'asc')->get();
        return response()->json($kecamatan);
    }

    public function getDesa($kecamatan_id)
    {
        $district = District::find($kecamatan_id);
        $code = $district ? $district->code : $kecamatan_id;
        $desa = Village::where('district_code', $code)->orderBy('name', 'asc')->get();
        return response()->json($desa);
    }
}
