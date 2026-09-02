<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pengguna');
    }

    /**
     * Get data for DataTables via AJAX.
     */
    public function data()
    {
        $users = User::select(['id', 'username', 'level', 'status']);
        
        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('status_badge', function ($user) {
                if ($user->status == 'aktif') {
                    return '<span class="badge bg-success">Aktif</span>';
                }
                return '<span class="badge bg-danger">Tidak Aktif</span>';
            })
            ->addColumn('aksi', function ($user) {
                return '
                    <button class="btn btn-sm btn-info btn-edit" data-id="' . $user->id . '"><i class="ri-pencil-line"></i></button>
                    <button class="btn btn-sm btn-danger btn-delete" data-id="' . $user->id . '"><i class="ri-delete-bin-line"></i></button>
                ';
            })
            ->rawColumns(['status_badge', 'aksi'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:100|unique:users',
            'password' => 'required|string|min:6',
            'level' => 'required|in:admin,operator',
        ]);

        try {
            User::create([
                'username' => $request->username,
                'password' => $request->password, 
                'level' => $request->level,
                'status' => 'aktif',    // Default status
            ]);
            return response()->json(['success' => 'Data pengguna berhasil disimpan.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menyimpan data pengguna: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'username' => 'required|string|max:100|unique:users,username,' . $id,
            'level' => 'required|in:admin,operator',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'string|min:6';
        }

        $request->validate($rules);

        try {
            $data = [
                'username' => $request->username,
                'level' => $request->level,
            ];

            if ($request->filled('password')) {
                $data['password'] = $request->password; // Handled by Model cast
            }

            $user->update($data);
            return response()->json(['success' => 'Data pengguna berhasil diubah.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengubah data pengguna: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(['success' => 'Data pengguna berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus data pengguna: ' . $e->getMessage()], 500);
        }
    }
}
