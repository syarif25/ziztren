<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterPeriode;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MasterPeriodeController extends Controller
{
    public function index()
    {
        return view('master_periode');
    }

    public function data()
    {
        $periodes = MasterPeriode::orderBy('tahun_masehi', 'desc')->orderBy('gelombang', 'desc')->get();

        return DataTables::of($periodes)
            ->addIndexColumn()
            ->addColumn('tgl_buka', function ($row) {
                return Carbon::parse($row->tgl_buka)->format('d-m-Y');
            })
            ->addColumn('tgl_tutup', function ($row) {
                return Carbon::parse($row->tgl_tutup)->format('d-m-Y');
            })
            ->addColumn('status', function ($row) {
                $badgeClass = $row->status == 'aktif' ? 'bg-success' : 'bg-secondary';
                return '<span class="badge ' . $badgeClass . '">' . ucfirst($row->status) . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-sm btn-info edit-btn" data-id="' . $row->id . '"><i class="ri-edit-line"></i> Edit</button>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_masehi' => 'required|integer',
            'gelombang' => 'required|integer',
            'tgl_buka' => 'required|date',
            'tgl_tutup' => 'required|date|after_or_equal:tgl_buka',
            'status' => 'required|in:aktif,tutup',
            'kuota' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            if ($request->status == 'aktif') {
                MasterPeriode::where('status', 'aktif')->update(['status' => 'tutup']);
            }

            MasterPeriode::create($request->all());

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Periode berhasil ditambahkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal: ' . $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $periode = MasterPeriode::findOrFail($id);
        return response()->json($periode);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tahun_masehi' => 'required|integer',
            'gelombang' => 'required|integer',
            'tgl_buka' => 'required|date',
            'tgl_tutup' => 'required|date|after_or_equal:tgl_buka',
            'status' => 'required|in:aktif,tutup',
            'kuota' => 'required|integer|min:1',
        ]);

        $periode = MasterPeriode::findOrFail($id);

        DB::beginTransaction();
        try {
            if ($request->status == 'aktif') {
                MasterPeriode::where('id', '!=', $id)->where('status', 'aktif')->update(['status' => 'tutup']);
            }

            $periode->update($request->all());

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Periode berhasil diupdate']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal: ' . $e->getMessage()], 500);
        }
    }

    public function activeInfo()
    {
        $periode = MasterPeriode::where('status', 'aktif')->first();
        if ($periode) {
            return response()->json([
                'success' => true,
                'data' => [
                    'gelombang' => $periode->gelombang,
                    'tgl_buka' => Carbon::parse($periode->tgl_buka)->format('d M Y'),
                    'tgl_tutup' => Carbon::parse($periode->tgl_tutup)->format('d M Y'),
                ]
            ]);
        }
        return response()->json(['success' => false]);
    }
}
