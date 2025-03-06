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
        $data  = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'kode_tarif'        => $value->kode_tarif,
                'tindakan'          => $value->tindakan,
                'kelompok_tindakan' => $value->kelompok_tindakan,
                'tipe'              => $value->tipe,
                'kategori_layanan'  => $value->kategori_layanan,
                'group_tindakan'    => $value->group_tindakan,
                'status_cito'       => $value->status_cito,
                'cito'              => $value->cito,
                'status'            => $value->status,
                'flat'              => $value->flat,
                'status_operasi'    => $value->status_operasi,
            ];
        }
        return response()->json($data, 200);
    }

    // Store
    public function store(Request $request)
    {
        $query = Tarif_tindakan::create([
            'kode_tarif'        => $request->kode,
            'tindakan'          => $request->nama_tindakan,
            'kelompok_tindakan' => $request->kelompok_tindakan,
            'tipe'              => $request->tipe,
            'kategori_layanan'  => $request->kategori_layanan,
            'group_tindakan'    => $request->group_tindakan,
            'status_cito'       => $request->status_cito == 'on' ? '1' : '0',
            'cito'              => $request->nilai_cito,
            'status'            => $request->status_tindakan == 'on' ? '1' : '0',
            'flat'              => $request->flat == 'on' ? '1' : '0',
            'status_operasi'    => $request->status_operasi,
        ]);
        if ($query) {
            return response()->json([
                'success' => true,
                'data'    => [],
                'message' => 'Data Berhasil Ditambahkan.',
            ], status: 200);
        } else {
            return response()->json([
                'success' => false,
                'data'    => [],
                'message' => 'Data Gagal Ditambahkan.',
            ], status: 400);
        }
    }

    // Update
    public function update(Request $request, $id)
    {
        dd($request);
        //    $query = SKTarif::where('id', $id)->update([
        //        'no_sk' => $request->no_sk,
        //        'tgl_efektif_mulai' => convertDmyToYmd($request->tgl_mulai),
        //        'tgl_efektif_akhir' => convertDmyToYmd($request->tgl_akhir),
        //        'deskripsi' => $request->deskripsi,
        //    ]);
        //    if ($query) {
        //        return response()->json([
        //            'success' => true,
        //            'data' => [],
        //            'message' => 'Data Berhasil Diubah.',
        //        ], status: 200);
        //    } else {
        //        return response()->json([
        //            'success' => false,
        //            'data' => [],
        //            'message' => 'Data Gagal Diubah.',
        //        ], status: 400);
        //    }
    }
}
