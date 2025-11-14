<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaterData\Kondisiasets;

class KondisiAsetController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title' => 'Kondisi Asset',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Kondisi Asset',
        ];
        return view('master-data.kondisi-aset.kondisi-aset', $data);
    }

    // Views Table
    public function views()
    {
        $query = Kondisiasets::all();
        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id'        => $value->id,
                'kode'      => $value->kode,
                'nama'      => $value->nama,
                'status'    => $value->status,
            ];
        }
        return response()->json($data, 200);
    }

    // Simpan
    public function store(Request $request)
    {
        $query = Kondisiasets::create([
            'kode'      => $request->kode,
            'nama'      => $request->nama,
            'status'    => $request->status == 'on' ? '1' : '0',
        ]);
        if ($query) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Data Berhasil Ditambahkan.',
            ], status: 200);
        }else{
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
        $query = Kondisiasets::where('id', $id)->update([
            'kode'      => $request->kode,
            'nama'      => $request->nama,
            'status'    => $request->status == 'on' ? '1' : '0',
        ]);
        if ($query) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Data Berhasil Diubah.',
            ], status: 200);
        }else{
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
        $query = Kondisiasets::where('id', $id)->delete();
        if ($query) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Data Berhasil Dihapus.',
            ], status: 200);
        }else{
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
        $query = Kondisiasets::where('id', $id)->update([
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
