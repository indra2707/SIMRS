<?php

namespace App\Http\Controllers;

use App\Models\Tarif\Tarif_tindakan;
use Illuminate\Http\Request;

class GlobalController extends Controller
{
    // ----------------------------------------------------------------
    // Menu Tarif Tindakkan Select2
    public function tarif_tindakan()
    {
        return response()->json([
            'success' => true,
            'data' => [
                [
                    'id' => 1,
                    'text' => 'Tarif Tindakan',
                ],
                [
                    'id' => 2,
                    'text' => 'Tarif Obat',
                ],
                [
                    'id' => 3,
                    'text' => 'Tarif Laboratorium',
                ],
                [
                    'id' => 4,
                    'text' => 'Tarif Radiologi',
                ],
                [
                    'id' => 5,
                    'text' => 'Tarif Operasi',
                ],
            ],
            'message' => 'Data Berhasil Ditambahkan.',
        ], status: 200);
    }
    // ----------------------------------------------------------------
    // Generate Nomor Kode Tarif Tindakkan
    public function generate_kode_tarif_tindakan($id)
    {
        $query = Tarif_tindakan::select('MAX(RIGHT(kode_tarif, 10)) as kode')->where('id', $id);
        if ($query->count() > 0) {
            $query = $query->first();
            $kode = "TND" . sprintf("%07s", $query->kode + 1);
            return response()->json([
                'success' => true,
                'data' => $kode,
            ]);
        }else{
            return response()->json([
                'success' => true,
                'data' => "TND00000001",
            ]);
        }
    }
}
