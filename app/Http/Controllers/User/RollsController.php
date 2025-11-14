<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\Rolls;
use Illuminate\Http\Request;

class RollsController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title' => 'Roll',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Roll',
        ];
        return view('user.roll.roll', $data);
    }

    // Views Table
    public function views()
    {
        $query = Rolls::all();
        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id' => $value->id,
                'nama' => $value->nama,
                'status' => $value->status,
                'menu' => $value->menu,
            ];
        }
        return response()->json($data, 200);
    }

    // Simpan
    public function store(Request $request)
    {
        $query = Rolls::create([
            'nama' => $request->nama,
            'status' => $request->status == 'on' ? '1' : '0',
        ]);
        if ($query) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Data Berhasil Ditambahkan.',
            ], status: 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Data Gagal Ditambahkan.',
            ], status: 400);
        }
    }

    // Update
    public function update(Request $request, $id)
    {
        $query = Rolls::where('id', $id)->update([
            'nama' => $request->nama,
            'status' => $request->status == 'on' ? '1' : '0',
        ]);
        if ($query) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Data Berhasil Diubah.',
            ], status: 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Data Gagal Diubah.',
            ], status: 400);
        }
    }

    // Update Menu
    public function updateMenu(Request $request, $id)
    {
        // Validasi
        $request->validate([
            'menu' => 'nullable|array',
        ]);

        // Simpan sebagai JSON (agar bisa disimpan array permission)
        $query = Rolls::where('id', $id)->update([
            'menu' => json_encode($request->menu ?? []),
        ]);

        if ($query) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Akses menu berhasil diperbarui.',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Gagal memperbarui akses menu.',
            ], 400);
        }
    }

    // Delete
    public function destroy($id)
    {
        $query = Rolls::where('id', $id)->delete();
        if ($query) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Data Berhasil Dihapus.',
            ], status: 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Data Gagal Dihapus.',
            ], status: 400);
        }
    }

    // update status check
    public function updateStatus(Request $request, $id)
    {
        $query = Rolls::where('id', $id)->update([
            'status' => $request->status,
        ]);
        if ($query) {
            return response()->json([
                'success' => true,
                'message' => 'Sukses mengubah status menjadi ' . ($request->status === '1' ? 'Aktif' : 'Tidak Aktif'),
                'data' => [],
            ], status: 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status.',
                'data' => [],
            ], status: 400);
        }
    }
}
