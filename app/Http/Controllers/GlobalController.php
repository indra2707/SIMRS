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

    public function optionsSelectSpesialis(Request $request)
    {
        $query = DB::table('spesialisses')
            ->where('status', '1')
            ->where('nama', 'like', "%$request->search%")
            ->get();
        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id' => $value->id,
                'text' => $value->nama,
            ];
        }
        return response()->json([
            'data' => $data
        ], 200);
    }
}
