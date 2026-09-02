<?php

namespace App\Http\Controllers;

use App\Models\Lembaga;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LembagaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('lembaga');
    }

    /**
     * Data untuk DataTables Server-Side Processing
     */
    public function data()
    {
        $lembagas = Lembaga::select(['id', 'kode_lembaga', 'nama_lembaga', 'bidang', 'jenis_lembaga']);

        return DataTables::of($lembagas)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $btn = '<button class="btn btn-sm btn-info edit-btn" data-id="' . $row->id . '"><i class="ri-pencil-line"></i></button> ';
                $btn .= '<button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '"><i class="ri-delete-bin-line"></i></button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_lembaga' => 'required|string|max:50|unique:lembagas,kode_lembaga',
            'nama_lembaga' => 'required|string|max:150',
            'bidang' => 'nullable|string|max:100',
            'jenis_lembaga' => 'required|in:pagi,sore',
        ]);

        try {
            Lembaga::create($validated);
            return response()->json(['success' => true, 'message' => 'Lembaga berhasil ditambahkan.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $lembaga = Lembaga::findOrFail($id);
        return response()->json($lembaga);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $lembaga = Lembaga::findOrFail($id);

        $validated = $request->validate([
            'kode_lembaga' => 'required|string|max:50|unique:lembagas,kode_lembaga,' . $lembaga->id,
            'nama_lembaga' => 'required|string|max:150',
            'bidang' => 'nullable|string|max:100',
            'jenis_lembaga' => 'required|in:pagi,sore',
        ]);

        try {
            $lembaga->update($validated);
            return response()->json(['success' => true, 'message' => 'Lembaga berhasil diperbarui.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $lembaga = Lembaga::findOrFail($id);
            $lembaga->delete();
            return response()->json(['success' => true, 'message' => 'Lembaga berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
