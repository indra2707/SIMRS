<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MaterData\Poli_tindakans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Psy\debug;

class Poli_tindakanController extends Controller
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
    public function views(Request $request)
    {

        // $query = Poli_tindakans::all();

        $query = DB::table('poli_tindakans')
               ->where('kode_poli', $request->kode)
               ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id1' => $value->id,
                'kode_tindakan' => $value->kode_tindakan,
                // 'kode_poli' => $value->kode,
                'status' => $value->status,
            ];
        }
        return response()->json($data, 200);
    }
    // Store
    public function store(Request $request)
    {
        $request->validate([
            'tindakan' => 'required',
            // 'kode_poli' => 'required',
            'status' => 'required',
        ]);
        $query = Poli_tindakans::create([
            'kode_poli' => $request->kode,
            'kode_tindakan' => $request->tindakan,
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
        $query = Poli_tindakans::where('id', $id)->update([
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
        $query = Poli_tindakans::where('id', $id)->update([
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
    public function destroy($id1)
    {
        $query = Poli_tindakans::where('id', $id1)->delete();
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
