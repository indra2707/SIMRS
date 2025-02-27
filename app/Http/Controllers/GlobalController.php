<?php

namespace App\Http\Controllers;

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
}
