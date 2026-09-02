<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\Province;
use Yajra\DataTables\Facades\DataTables;

class ProvinsiController extends Controller
{
    /**
     * Display the Provinsi view.
     */
    public function index()
    {
        return view('provinsi');
    }

    /**
     * Return JSON for DataTables Server-Side Processing
     */
    public function data()
    {
        $provinces = Province::query();

        return DataTables::of($provinces)
            ->addIndexColumn()
            ->make(true);
    }
}
