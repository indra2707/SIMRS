<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MaterData\Poli_obats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Psy\debug;

class Poli_obatController extends Controller
{
    // Index
    // public function index()
    // {
    //     $data = [
    //         'title' => 'Poliklinik',
    //         'menuTitle' => 'Master Data',
    //         'menuSubtitle' => 'Poliklinik',
    //     ];
    //     return view('master-data.poli.poli', $data);
    // }

    // Views Table
    public function views(Request $request)
    {

        $query = DB::table('poli_obats')
               ->where('kode_poli', $request->kode)
               ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id1' => $value->id,
                'kode_obat' => $value->kode_obat,
                'status' => $value->status,
            ];
        }
        return response()->json($data, 200);
    }

    // Store
    public function store(Request $request)
    {
        $query = Poli_obats::create([
            'kode_poli' => $request->kode,
            'kode_obat' => $request->kode_obat,
            'status' => $request->status2 == 'on' ? '1' : '0',
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
    public function updateStatus(Request $request, $id2)
    {
        $query = Poli_obats::where('id', $id2)->update([
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
            // 'kode' => 'required',
            'nama' => 'required',
            'kategori' => 'required',
        ]);
        $query = Poli_obats::where('id', $id)->update([
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
    public function destroy($id2)
    {
        $query = Poli_obats::where('id', $id2)->delete();
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
