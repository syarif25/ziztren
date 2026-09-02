<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\City;
use Yajra\DataTables\Facades\DataTables;

class KotaController extends Controller
{
    /**
     * Display the Kota view.
     */
    public function index()
    {
        return view('kota');
    }

    /**
     * Return JSON for DataTables Server-Side Processing
     */
    public function data()
    {
        $cities = City::with('province');

        return DataTables::of($cities)
            ->addIndexColumn()
            ->addColumn('provinsi', function ($city) {
                return $city->province ? $city->province->name : '-';
            })
            ->make(true);
    }
}
