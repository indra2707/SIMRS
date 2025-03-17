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
                'id'                => $value->id,
                'kode_tarif'        => $value->kode_tarif,
                'tindakan'          => $value->  tindakan,
                'kategori'          => $value->kategori,
                'cito'              => $value->cito,
                'coa_pendapatan_rj' => $value->coa_pendapatan_rj,
                'coa_pendapatan_ri'  => $value->coa_pendapatan_ri,
                'coa_reduksi_rj'    => $value->coa_reduksi_rj,
                'coa_reduksi_ri'    => $value->coa_reduksi_ri,
                'coa_mcu_onsite'    => $value->coa_mcu_onsite,
                'coa_mcu_insite'    => $value->coa_mcu_insite,
                'status'            => $value->status,
            ];
        }
        return response()->json($data, 200);
    }

    // Store
    public function store(Request $request)
    {
        $query = Tarif_tindakan::create([
            'kode_tarif'        => $request->kode_tarif,
            'tindakan'          => $request->tindakan,
            'kategori'          => $request->kategori,
            'cito'              => $request->cito,
            'status'            => $request->status == 'on' ? '1' : '0',
            'coa_pendapatan_rj' => $request->coa_pendapatan_rj,
            'coa_pendapatan_ri' => $request->coa_pendapatan_ri,
            'coa_reduksi_rj'    => $request->coa_reduksi_rj,
            'coa_reduksi_ri'    => $request->coa_reduksi_ri,
            'coa_mcu_onsite'    => $request->coa_mcu_onsite,
            'coa_mcu_insite'    => $request->coa_mcu_insite,

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

     // update status check
     public function updateStatus(Request $request, $id)
     {
         $query = Tarif_tindakan::where('id', $id)->update([
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
           $query = Tarif_tindakan::where('id', $id)->update([
            'tindakan'          => $request->tindakan,
            'kategori'          => $request->kategori,
            'cito'              => $request->cito,
            // 'status'            => $request->status == 'on' ? '1' : '0',
            'coa_pendapatan_rj' => $request->coa_pendapatan_rj,
            'coa_pendapatan_ri' => $request->coa_pendapatan_ri,
            'coa_reduksi_rj'    => $request->coa_reduksi_rj,
            'coa_reduksi_ri'    => $request->coa_reduksi_ri,
            'coa_mcu_onsite'    => $request->coa_mcu_onsite,
            'coa_mcu_insite'    => $request->coa_mcu_insite,
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
        $query = Tarif_tindakan::where('id', $id)->delete();
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
