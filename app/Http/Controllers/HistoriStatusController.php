<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HistoriStatusBeasiswa;
use Yajra\DataTables\Facades\DataTables;

class HistoriStatusController extends Controller
{
    public function index()
    {
        return view('histori-status.index');
    }

    public function data()
    {
        $histori = HistoriStatusBeasiswa::whereHas('pengajuan', function ($q) {
            $q->forUser();
        })->with('pengajuan.santri')->select('histori_status_beasiswas.*');

        return DataTables::of($histori)
            ->addIndexColumn()
            ->addColumn('pengajuan_id', function ($row) {
                return $row->pengajuan_id;
            })
            ->addColumn('santri_nama', function ($row) {
                return $row->pengajuan && $row->pengajuan->santri ? $row->pengajuan->santri->nama_lengkap : '-';
            })
            ->editColumn('status', function ($row) {
                $badges = [
                    'draft' => 'secondary',
                    'Draft' => 'secondary',
                    'proses' => 'primary',
                    'Proses' => 'primary',
                    'acc' => 'success',
                    'ACC' => 'success',
                    'pencairan' => 'info',
                    'Pencairan' => 'info'
                ];
                $color = $badges[$row->status] ?? 'dark';
                return '<span class="badge bg-' . $color . '">' . ucfirst($row->status) . '</span>';
            })
            ->editColumn('tanggal_bantuan', function ($row) {
                return $row->tanggal_bantuan ? \Carbon\Carbon::parse($row->tanggal_bantuan)->translatedFormat('d M Y') : '-';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('Y-m-d H:i:s') : '-';
            })
            ->addColumn('action', function($row){
                $btn = '<a href="javascript:void(0)" data-id="'.$row->id.'" class="btn btn-sm btn-info edit-histori"><i class="ri-edit-2-line"></i></a>';
                $btn .= ' <a href="javascript:void(0)" data-id="'.$row->id.'" class="btn btn-sm btn-danger delete-histori"><i class="ri-delete-bin-line"></i></a>';
                return $btn;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function edit($id)
    {
        $histori = HistoriStatusBeasiswa::findOrFail($id);
        return response()->json($histori);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'tanggal_bantuan' => 'nullable|date',
            'catatan' => 'nullable|string'
        ]);

        $histori = HistoriStatusBeasiswa::findOrFail($id);
        $histori->update([
            'status' => $request->status,
            'tanggal_bantuan' => $request->tanggal_bantuan,
            'catatan' => $request->catatan,
        ]);

        return response()->json(['success' => true, 'message' => 'Histori berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $histori = HistoriStatusBeasiswa::findOrFail($id);
        $histori->delete();

        return response()->json(['success' => true, 'message' => 'Histori berhasil dihapus.']);
    }
}
