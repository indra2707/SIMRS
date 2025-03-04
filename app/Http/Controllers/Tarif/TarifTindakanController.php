<?php

namespace App\Http\Controllers\Tarif;

use App\Http\Controllers\Controller;
use App\Models\Tarif\Tarif_tindakan;
use Illuminate\Http\Request;

class TarifTindakanController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title'        => 'Tarif Tindakan',
            'menuTitle'    => 'Master Data',
            'menuSubtitle' => 'Tarif Tindakan',
        ];
        return view('tarif.tindakan.index', $data);
    }

    // Views Table
    public function views()
    {
        $query = Tarif_tindakan::all();
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

    public function form_tarif()
    {
        $data = [
            'title'        => 'Tarif Tindakan',
            'menuTitle'    => 'Master Data',
            'menuSubtitle' => 'Form Tambah Tarif',
        ];
        return view('master-data.tarif.form-tarif-tindakan', $data);
    }
}
