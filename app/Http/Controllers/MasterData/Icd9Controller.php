<?php


namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MaterData\Icd9s;
use Illuminate\Http\Request;


class Icd9Controller extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title' => 'ICD-9',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'ICD-9',
        ];
        return view('master-data.icd-9.icd-9', $data);
    }

    // Views Table
    public function views()
    {
        $query = Icd9s::all();
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
        $query = Icd9s::create([
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
        $query = Icd9s::where('id', $id)->update([
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
        $query = Icd9s::where('id', $id)->update([
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
        $query = Icd9s::where('id', $id)->delete();
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
