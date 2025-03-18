<?php

namespace App\Http\Controllers;

use App\Models\Tarif\Tarif_tindakan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GlobalController extends Controller
{
    // Generate Nomor Kode Tarif Tindakkan
    public function generateKodeTarifTindakan($id)
    {
        $query = Tarif_tindakan::select(columns: Tarif_tindakan::raw("MAX(RIGHT(kode_tarif, 7)) as kode"))->where('kode_tarif', $id);
        if ($query->count() > 0) {
            $query = $query->first();
            $kode = "TND" . sprintf("%07s", $query->kode + 1);
            return response()->json([
                'success' => true,
                'data' => $kode,
            ]);
        } else {
            return response()->json([
                'success' => true,
                'data' => "TND0000001",
            ]);
        }
    }

    // Update Status
    public function updateStatus(Request $request, $id)
    {
        $query = DB::table($request->table)
            ->where('id', $id)
            ->update(['status' => $request->status]);
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


    // select spesialis
    public function optionsSelectSpesialis(Request $request)
    {
        if ($request->values != "") {
            $where = [
                ['status', '=', '1'],
                ['id', '=', $request->values],
                ['nama', 'like', "%$request->search%"]
            ];
        } else {
            $where = [
                ['status', '=', '1'],
                ['nama', 'like', "%$request->search%"]
            ];
        }
        $query = DB::table('spesialisses')
            ->where($where)
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[$key]['id']   = $value->id;
            $data[$key]['text'] = $value->nama;
        }
        return response()->json([
            'data' => $data
        ], 200);
    }

    // select petugas
    public function optionsSelectPetugas(Request $request)
    {
        if ($request->values != "") {
            $where = [
                ['status', '=', '1'],
                ['id', '=', $request->values],
                ['nama', 'like', "%$request->search%"]
            ];
        } else {
            $where = [
                ['status', '=', '1'],
                ['nama', 'like', "%$request->search%"]
            ];
        }
        $query = DB::table('petugas')
            ->where($where)
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[$key]['id']   = $value->id;
            $data[$key]['text'] = $value->nama;
        }
        return response()->json([
            'data' => $data
        ], 200);
    }


    // select poli
    public function optionsSelectPoli(Request $request)
    {
        if ($request->values != "") {
            $where = [
                ['status', '=', '1'],
                ['id', '=', $request->values],
                ['nama', 'like', "%$request->search%"]
            ];
        } else {
            $where = [
                ['status', '=', '1'],
                ['nama', 'like', "%$request->search%"]
            ];
        }
        $query = DB::table('polis')
            ->where($where)
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[$key]['id']   = $value->id;
            $data[$key]['text'] = $value->nama;
        }
        return response()->json([
            'data' => $data
        ], 200);
    }

     // Store COA
     public function optionsSelectCoa(Request $request)
     {
         if ($request->value != null) {
             $query = DB::table('coas')
             ->where('status', '1')
             ->where('id', '=', $request->value)
             ->where('kategori', 'Tindakan')
             ->get();
         }else{
             $query = DB::table('coas')
             ->where('status', '1')
             ->where('kategori', 'Tindakan')
             ->where('nama', 'like', "%$request->search%")
             ->where('kode', 'like', "%$request->search%")
             ->get();
         }


         $data = [];
         foreach ($query as $key => $value) {
             $data[$key]['id']   = $value->id;
             $data[$key]['text'] = $value->kode .'-'. $value->nama;
         }
         return response()->json([
             'data' => $data
         ], 200);
     }


     // select Tindakan
    public function optionsSelectTindakan(Request $request)
    {
        if ($request->values != "") {
            $where = [
                ['status', '=', '1'],
                ['id', '=', $request->values],
                ['tindakan', 'like', "%$request->search%"]
            ];
        } else {
            $where = [
                ['status', '=', '1'],
                ['tindakan', 'like', "%$request->search%"]
            ];
        }
        $query = DB::table('tarif_tindakans')
            ->where($where)
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[$key]['id']   = $value->id;
            $data[$key]['text'] = $value->tindakan;
        }
        return response()->json([
            'data' => $data
        ], 200);
    }
}
