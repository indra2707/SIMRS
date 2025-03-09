<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MaterData\Polis;
use Illuminate\Http\Request;

class PoliController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title' => 'Poliklinik',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Poliklinik',
        ];
        return view('master-data.poli.poli', $data);
    }

    // Views Table
    public function views()
    {
        $query = Polis::all();

        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id' => $value->id,
                'kode' => $value->kode,
                'kategori' => $value->kategori,
                'nama' => $value->nama,
                'status' => $value->status,
            ];
        }
        return response()->json($data, 200);
    }
    // Store
    public function store(Request $request)
    {
        $request->validate([
            // 'kode' => 'required',
            'nama' => 'required',
            'kategori' => 'required',
            'status' => 'required',
        ]);
        $query = Polis::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'created_by' => $request->get('username'),
            'kategori' => $request->kategori,
            'status' => $request->status == 'on' ? '1' : '0',
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
        $request->validate([
            // 'kode' => 'required',
            'nama' => 'required',
            'kategori' => 'required',
        ]);
        $query = Polis::where('id', $id)->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'status' => $request->status == 'on' ? '1' : '0',
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
        $query = Polis::where('id', $id)->delete();
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
}
