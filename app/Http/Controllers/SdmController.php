<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterData\Lokasis;
use App\Http\Controllers\Controller;

class SdmController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Lokasi',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Lokasi',
        ];
        return view('sdm.sdm', $data);
    }

    public function views()
    {

        $query = Lokasis::all();
        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id'        => $value->id,
                'nama'      => $value->nama,
                'status'    => $value->status,
            ];
        }
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $query = Lokasis::create([
            'nama'      => $request->nama,
            'status'    => $request->status == 'on' ? '1' : '0',
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
        $query = Lokasis::where('id', $id)->update([
            'nama'      => $request->nama,
            'status'    => $request->status == 'on' ? '1' : '0',
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

    // Delete
    public function destroy($id)
    {
        $query = Lokasis::where('id', $id)->delete();
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

    public function updateStatus(Request $request, $id)
    {
        $query = Lokasis::where('id', $id)->update([
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
