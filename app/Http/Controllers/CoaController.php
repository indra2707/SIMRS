<?php

namespace App\Http\Controllers;

use App\Models\Coas;
use Illuminate\Http\Request;

class CoaController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title' => 'COA',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'COA',
        ];
        return view('master-data.coa.coa', $data);
    }

    // Views Table
    public function views()
    {
        $query = Coas::all();

        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id' => $value->id,
                'kode' => $value->kode,
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
            'kode' => 'required',
            'nama' => 'required',
            'status' => 'required',
        ]);
        $query = Coas::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
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

    // update status check
    public function updateStatus(Request $request, $id)
    {
        $query = Coas::where('id', $id)->update([
            'status' => $request->status,
        ]);
        if ($query) {
            return response()->json([
                'success' => true,
                'message' => 'Sukses mengubah status menjadi ' . ($request->status === '1' ? 'Aktif' : 'Tidak Aktif'),
                'data' => [],
            ], status: 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status.',
                'data' => [],
            ], status: 400);
        }
    }

    // Update
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required',
            'nama' => 'required',
        ]);
        $query = Coas::where('id', $id)->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
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
        $query = Coas::where('id', $id)->delete();
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
