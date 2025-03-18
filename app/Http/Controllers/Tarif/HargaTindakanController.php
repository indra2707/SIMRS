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
                'kode_tarif'        => $value->kode_tarif,
                'kode_sk'           => $value->kode_sk,
                'sk'                => $value->sk,
                'kelas_1'           => number_format($value->kelas_1, 2, ',', '.'),
                'kelas_2'           => number_format($value->kelas_2, 2, ',', '.'),
                'kelas_3'           => number_format($value->kelas_3, 2, ',', '.'),
                'kelas_isolasi'     => number_format($value->kelas_isolasi, 2, ',', '.'),
                'kelas_intensif'    => number_format($value->kelas_intensif, 2, ',', '.'),
                'kelas_vip'         => number_format($value->kelas_vip, 2, ',', '.'),
                'kelas_vvip'        => number_format($value->kelas_vvip, 2, ',', '.'),
            ];
        }
        return response()->json($data, 200);
    }

    // Store
    public function store(Request $request)
    {
        $query = Harga_tindakans::create([
            'kode_sk'             => $request->kode_sk,
            'kode_tarif'          => $request->kode_tarif,
            'kelas_1'             => floatval(str_replace(',', '',str_replace('Rp ', '', $request->kelas_1))),
            'kelas_2'             => floatval(str_replace(',', '',str_replace('Rp ', '', $request->kelas_2))),
            'kelas_3'             => floatval(str_replace(',', '',str_replace('Rp ', '', $request->kelas_3))),
            'kelas_isolasi'       => floatval(str_replace(',', '',str_replace('Rp ', '', $request->kelas_isolasi))),
            'kelas_intensif'      => floatval(str_replace(',', '',str_replace('Rp ', '', $request->kelas_intensif))),
            'kelas_vip'           => floatval(str_replace(',', '',str_replace('Rp ', '', $request->kelas_vip))),
            'kelas_vvip'          => floatval(str_replace(',', '',str_replace('Rp ', '', $request->kelas_vvip))),

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
    public function update(Request $request, $id1)
    {
        $query = Harga_tindakans::where('id', $id1)->update([
            'kode_sk'             => $request->kode_sk,
            'kode_tarif'          => $request->kode_tarif,
            'kelas_1'             => floatval(str_replace(',', '',str_replace('Rp ', '', $request->kelas_1))),
            'kelas_2'             => floatval(str_replace(',', '',str_replace('Rp ', '', $request->kelas_2))),
            'kelas_3'             => floatval(str_replace(',', '',str_replace('Rp ', '', $request->kelas_3))),
            'kelas_isolasi'       => floatval(str_replace(',', '',str_replace('Rp ', '', $request->kelas_isolasi))),
            'kelas_intensif'      => floatval(str_replace(',', '',str_replace('Rp ', '', $request->kelas_intensif))),
            'kelas_vip'           => floatval(str_replace(',', '',str_replace('Rp ', '', $request->kelas_vip))),
            'kelas_vvip'          => floatval(str_replace(',', '',str_replace('Rp ', '', $request->kelas_vvip))),
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
    public function destroy($id1)
    {
        $query = Harga_tindakans::where('id', $id1)->delete();
        if ($query) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Data Berhasil Dihapus.',
            ], status: 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Data Gagal Dihapus.',
            ], status: 400);
        }
    }
}
