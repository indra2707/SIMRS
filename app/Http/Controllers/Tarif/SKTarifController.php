<?php

namespace App\Http\Controllers\Tarif;

use App\Http\Controllers\Controller;
use App\Models\Tarif\SKTarif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SKTarifController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'SK Tarif',
            'menuTitle' => 'Tarif',
            'menuSubtitle' => 'SK Tarif',
        ];
        return view('Tarif.SKTarif.sk-tarif', $data);
    }

    public function views()
    {
        $query = SKTarif::all();
        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id' => $value->id,
                'no_sk' => $value->no_sk,
                'tgl_mulai' => $value->tgl_efektif_mulai,
                'tgl_akhir' => $value->tgl_efektif_akhir,
                'deskripsi' => $value->deskripsi,
                'status' => $value->status,
            ];
        }
        return response()->json($data, 200);
    }

    // Store
    public function store(Request $request)
    {
        $query = SKTarif::create([
            'no_sk' => $request->no_sk,
            'tgl_mulai' => $request->tgl_efektif_mulai,
            'tgl_akhir' => $request->tgl_efektif_akhir,
            'deskripsi' => $request->deskripsi,
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

    // update status check
    public function updateStatus(Request $request, $id)
    {
        $query = SKTarif::where('id', $id)->update([
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

    // Update
    public function update(Request $request, $id)
    {
        $query = SKTarif::where('id', $id)->update([
            'no_sk' => $request->no_sk,
            'tgl_mulai' => $request->tgl_efektif_mulai,
            'tgl_akhir' => $request->tgl_efektif_akhir,
            'deskripsi' => $request->deskripsi,
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

     // Delete
     public function destroy($id)
     {
         $query = SKTarif::where('id', $id)->delete();
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
