<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MaterData\Biaya;
use Illuminate\Http\Request;

class BiayaController extends Controller
{
     public function index()
    {
        $data = [
            'title' => 'Biaya SPD',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Biaya-SPD',
        ];
        return view('master-data.biayaSpd.biaya', $data);
    }

      public function views()
    {
        $query = Biaya::all();
        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id'        => $value->id,
                'nama'      => $value->nama,
                'harga_utama'    => $value->harga_utama,
                'harga_madya'    => $value->harga_madya,
                'harga_biasa'    => $value->harga_biasa,
                'status'    => $value->status,
            ];
        }
        return response()->json($data, 200);
    }

    public function store(Request $request)  {

        $query = Biaya::create([
            'nama'      => $request->nama,
            'harga_utama'    => $request->harga_utama,
            'harga_madya'    => $request->harga_madya,
            'harga_biasa'    => $request->harga_biasa,
            'status'    => $request->status == 'on' ? '1' : '0',
        ]);

        if ($query) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Data Berhasil Disimpan.',
            ], status: 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Data Gagal Disimpan.',
            ], status: 200);
        }
    }

    public function update(Request $request, $id)  {

        $query = Biaya::where('id', $id)->update([
            'nama'      => $request->nama,
            'harga_utama'    => $request->harga_utama,
            'harga_madya'    => $request->harga_madya,
            'harga_biasa'    => $request->harga_biasa,
            'harga_biasa'    => $request->harga_biasa,
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
            ], status: 200);
        }
    }

    public function updateStatus(Request $request, $id)  {

        $query = Biaya::where('id', $id)->update([
            'status'    => $request->status,
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

    public function destroy($id)  {
        $query = Biaya::where('id', $id)->delete();
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
