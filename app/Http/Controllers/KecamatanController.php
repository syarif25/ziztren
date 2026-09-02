<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\District;
use Yajra\DataTables\Facades\DataTables;

class KecamatanController extends Controller
{
    /**
     * Display the Kecamatan view.
     */
    public function index()
    {
        return view('kecamatan');
    }

    /**
     * Return JSON for DataTables Server-Side Processing
     */
    public function data()
    {
        $districts = District::with('city.province');

        return DataTables::of($districts)
            ->addIndexColumn()
            ->addColumn('kota', function ($district) {
                return $district->city ? $district->city->name : '-';
            })
            ->addColumn('provinsi', function ($district) {
                return ($district->city && $district->city->province) ? $district->city->province->name : '-';
            })
            ->make(true);
    }
}
