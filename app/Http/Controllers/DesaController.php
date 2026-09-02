<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\Village;
use Yajra\DataTables\Facades\DataTables;

class DesaController extends Controller
{
    /**
     * Display the Desa view.
     */
    public function index()
    {
        return view('desa');
    }

    /**
     * Return JSON for DataTables Server-Side Processing
     */
    public function data()
    {
        $villages = Village::with('district.city.province');

        return DataTables::of($villages)
            ->addIndexColumn()
            ->addColumn('kecamatan', function ($village) {
                return $village->district ? $village->district->name : '-';
            })
            ->addColumn('kota', function ($village) {
                return ($village->district && $village->district->city) ? $village->district->city->name : '-';
            })
            ->addColumn('provinsi', function ($village) {
                return ($village->district && $village->district->city && $village->district->city->province) ? $village->district->city->province->name : '-';
            })
            ->make(true);
    }
}
