<?php

namespace App\Http\Controllers\Tarif;

use App\Http\Controllers\Controller;
use App\Models\Tarif\Harga_tindakans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HargaTindakanController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title'        => 'Harga Tindakan',
            'menuTitle'    => 'Master Data',
            'menuSubtitle' => 'Harga Tindakan',
        ];
        return view('tarif.tindakan.index', $data);
    }

    // Views Table
    public function views(Request $request)
    {

        $query = DB::table('harga_tindakans')
            ->where('kode_tarif', $request->kode)
            ->join('sk_tarifs', 'sk_tarifs.id', '=', 'harga_tindakans.kode_sk')
            ->select('harga_tindakans.*', 'sk_tarifs.no_sk as sk')
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id1' => $value->id,
                'kode_tarif' => $value->kode_tarif,
                'kode_sk' => $value->kode_sk,
                'sk' => $value->sk,
                'kelas1' => $value->kelas1,
                'kelas2' => $value->kelas2,
                'kelas3' => $value->kelas3,
                'kelasisolasi' => $value->kelasisolasi,
                'kelasintensif' => $value->kelasintensif,
                'kelasvip' => $value->kelasvip,
                'kelasvvip' => $value->kelasintensif,

            ];
        }
        return response()->json($data, 200);
    }

    // Store
    public function store(Request $request)
    {
        $query = Harga_tindakans::create([
            'kode_tarif'         => $request->kode_tarif,
            'kode_sk'            => $request->tindakan,
            'kelas1'             => $request->kelas1,
            'kelas2'             => $request->kelas2,
            'kelas3'             => $request->kelas3,
            'kelasisolasi'       => $request->kelasisolasi,
            'kelasintensif'      => $request->kelasintensif,
            'kelasvip'           => $request->kelasvip,
            'kelasvvip'          => $request->kelasvvip,

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
    // public function update(Request $request, $id)
    // {
    //     $query = Tarif_tindakan::where('id', $id)->update([
    //         'tindakan'          => $request->tindakan,
    //         'kategori'          => $request->kategori,
    //         'cito'              => $request->cito,
    //         'status'            => $request->status == 'on' ? '1' : '0',
    //         'coa_pendapatan_rj' => $request->coa_pendapatan_rj,
    //         'coa_pendapatan_ri' => $request->coa_pendapatan_ri,
    //         'coa_reduksi_rj'    => $request->coa_reduksi_rj,
    //         'coa_reduksi_ri'    => $request->coa_reduksi_ri,
    //         'coa_mcu_onsite'    => $request->coa_mcu_onsite,
    //         'coa_mcu_insite'    => $request->coa_mcu_insite,
    //     ]);
    //     if ($query) {
    //         return response()->json([
    //             'success' => true,
    //             'data' => [],
    //             'message' => 'Data Berhasil Diubah.',
    //         ], status: 200);
    //     } else {
    //         return response()->json([
    //             'success' => false,
    //             'data' => [],
    //             'message' => 'Data Gagal Diubah.',
    //         ], status: 400);
    //     }
    // }


    // Delete
    // public function destroy($id)
    // {
    //     $query = Tarif_tindakan::where('id', $id)->delete();
    //     if ($query) {
    //         return response()->json([
    //             'success' => true,
    //             'data' => [],
    //             'message' => 'Data Berhasil Dihapus.',
    //         ], status: 200);
    //     } else {
    //         return response()->json([
    //             'success' => false,
    //             'data' => [],
    //             'message' => 'Data Gagal Dihapus.',
    //         ], status: 400);
    //     }
    // }
}
