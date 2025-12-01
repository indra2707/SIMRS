<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MaterData\Mutasis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MutasiController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title' => 'Mutasi',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Mutasi',
        ];
        return view('master-data.mutasi.mutasi', $data);
    }

    // Views Table
    public function views()
    {


        $query = Db::table('tbl_mutasis')
            ->join('tbl_asets', 'tbl_asets.id', '=', 'tbl_mutasis.id_aset')
            ->join('tbl_lokasis', 'tbl_lokasis.id', '=', 'tbl_mutasis.id_lokasi')
            ->join('tbl_lokasis as tbl_lokasis_new', 'tbl_lokasis_new.id', '=', 'tbl_mutasis.id_lokasi_new')
            ->join('tbl_kondisis', 'tbl_kondisis.id', '=', 'tbl_mutasis.id_kondisi')
            ->select('tbl_mutasis.*', 'tbl_asets.no_aset as no_aset', 'tbl_asets.nama as nama_aset','tbl_asets.no_aset as no_aset', 'tbl_asets.no_sn as no_sn', 'tbl_kondisis.nama as nama_kondisi', 'tbl_lokasis.nama as nama_lokasi_asal', 'tbl_lokasis_new.nama as nama_lokasi_tujuan')
            ->get();

        // $query = Mutasis::all();
        $data = [];
        foreach ($query as $key => $value) {

            $data[] = [
                'id' => $value->id,
                'id_aset' => $value->id_aset,
                'no_aset' => $value->no_aset,
                'nama_aset' => $value->nama_aset,
                'no_sn' => $value->no_sn,
                'tgl_mutasi' => convertYmdToDmy($value->tgl_mutasi),
                'id_lokasi' => $value->id_lokasi,
                'id_lokasi_new' => $value->id_lokasi_new,
                'id_kondisi' => $value->id_kondisi,
                'nama_kondisi' => $value->nama_kondisi,
                'nama_lokasi_asal' => $value->nama_lokasi_asal,
                'nama_lokasi_tujuan' => $value->nama_lokasi_tujuan,
                'keterangan' => $value->keterangan,

            ];
        }
        return response()->json($data, 200);
    }

    // Store
    public function store(Request $request)
    {
        $query = Mutasis::create([
            'id_aset' => $request->kode_aset,
            'tgl_mutasi' => convertDmyToYmd($request->tgl_mutasi),
            'id_lokasi' => $request->id_lokasi_lama,
            'id_lokasi_new' => $request->kode_lokasi,
            'id_kondisi' => $request->kode_kondisi_aset,
            'keterangan' => $request->keterangan,
        ]);

        $queryAset = DB::table('tbl_asets')
            ->where('id', $request->kode_aset)
            ->update([
                'id_lokasi' => $request->kode_lokasi,
                'id_kondisi' => $request->kode_kondisi_aset,
            ]);

        if ($query && $queryAset) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Data Berhasil Ditambahkan.',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Data Gagal Ditambahkan.',
            ], 400);
        }
    }

    // Update
    public function update(Request $request, $id)
{
    DB::beginTransaction();

    try {

        // Update tabel mutasi
        Mutasis::where('id', $id)->update([
            'id_aset'          => $request->kode_aset,
            'tgl_mutasi'       => convertDmyToYmd($request->tgl_mutasi),
            'id_lokasi'        => $request->id_lokasi_lama,
            'id_lokasi_new'    => $request->kode_lokasi,
            'id_kondisi'       => $request->kode_kondisi_aset,
            'keterangan'       => $request->keterangan,
        ]);

        // Update lokasi & kondisi aset
        DB::table('tbl_asets')
            ->where('id', $request->kode_aset)
            ->update([
                'id_lokasi'   => $request->kode_lokasi,
                'id_kondisi'  => $request->kode_kondisi_aset,
            ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Diubah.',
        ], 200);

    } catch (\Throwable $e) {

        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'Data Gagal Diubah. Error: ' . $e->getMessage(),
        ], 500);
    }
}

    // Delete
    public function destroy($id)
    {
        $query = Mutasis::where('id', $id)->delete();
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

    // update status check
    public function updateStatus(Request $request, $id)
    {
        $query = Mutasis::where('id', $id)->update([
            'aktif' => $request->aktif,
        ]);
        if ($query) {
            return response()->json([
                'success' => true,
                'message' => 'Sukses mengubah status menjadi ' . ($request->aktif === '1' ? 'Aktif' : 'Tidak Aktif'),
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



    // MutasiController.php
    public function getDetailAset($id)
    {
        $aset = DB::table('tbl_asets')
            ->leftJoin('tbl_lokasi', 'tbl_lokasi.id', '=', 'tbl_asets.id_lokasi')
            ->where('tbl_asets.id', $id)
            ->select(
                'tbl_asets.*',
                'tbl_asets.id_lokasi',
                'tbl_lokasi.nama_lokasi as lokasi_name'
            )
            ->first();

        if (!$aset) {
            return response()->json(['error' => 'Data aset tidak ditemukan'], 404);
        }

        return response()->json([
            'lokasi_lama' => $aset->lokasi_name ?? $aset->id_lokasi,
            'id_lokasi' => $aset->id_lokasi ?? null,
            'no_sn' => $aset->no_sn ?? null,
        ], 200);
    }


}
