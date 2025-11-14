<?php

namespace App\Http\Controllers;

use App\Models\Tarif\Tarif_tindakan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class GlobalController extends Controller
{
    // Generate Nomor Kode Tarif Tindakkan
    public function generateKodeTarifTindakan($id)
    {
        dd($id);
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


    // select spesialis
    public function optionsSelectSpesialis(Request $request)
    {
        if ($request->values != "") {
            $where = [
                ['status', '=', '1'],
                ['id', '=', $request->values],
                ['nama', 'like', "%$request->search%"]
            ];
        } else {
            $where = [
                ['status', '=', '1'],
                ['nama', 'like', "%$request->search%"]
            ];
        }
        $query = DB::table('spesialisses')
            ->where($where)
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[$key]['id'] = $value->id;
            $data[$key]['text'] = $value->nama;
        }
        return response()->json([
            'data' => $data
        ], 200);
    }

    // select petugas
    public function optionsSelectPetugas(Request $request)
    {
        if ($request->values != "") {
            $where = [
                ['status', '=', '1'],
                ['id', '=', $request->values],
                ['nama', 'like', "%$request->search%"]
            ];
        } else {
            $where = [
                ['status', '=', '1'],
                ['nama', 'like', "%$request->search%"]
            ];
        }
        $query = DB::table('petugas')
            ->where($where)
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[$key]['id'] = $value->id;
            $data[$key]['text'] = $value->nama;
        }
        return response()->json([
            'data' => $data
        ], 200);
    }


    // select poli
    public function optionsSelectPoli(Request $request)
    {
        if ($request->values != "") {
            $where = [
                ['status', '=', '1'],
                ['id', '=', $request->values],
                ['nama', 'like', "%$request->search%"]
            ];
        } else {
            $where = [
                ['status', '=', '1'],
                ['nama', 'like', "%$request->search%"]
            ];
        }
        $query = DB::table('polis')
            ->where($where)
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[$key]['id'] = $value->id;
            $data[$key]['text'] = $value->nama;
        }
        return response()->json([
            'data' => $data
        ], 200);
    }

    // Store COA
    public function optionsSelectCoa(Request $request)
    {
        if ($request->value != null) {
            $query = DB::table('coas')
                ->where('status', '1')
                ->where('id', '=', $request->value)
                ->where('kategori', 'Tindakan')
                ->get();
        } else {
            $query = DB::table('coas')
                ->where('status', '1')
                ->where('kategori', 'Tindakan')
                ->where('nama', 'like', "%$request->search%")
                //  ->where('kode', 'like', "%$request->search%")
                ->get();
        }
        $data = [];
        foreach ($query as $key => $value) {
            $data[$key]['id'] = $value->id;
            $data[$key]['text'] = $value->kode . ' - ' . $value->nama;
        }
        return response()->json([
            'data' => $data
        ], 200);
    }

    // select Tindakan
    public function optionsSelectTindakan(Request $request)
    {
        if ($request->values != "") {
            $where = [
                ['status', '=', '1'],
                ['id', '=', $request->values],
                ['tindakan', 'like', "%$request->search%"]
            ];
        } else {
            $where = [
                ['status', '=', '1'],
                ['tindakan', 'like', "%$request->search%"]
            ];
        }
        $query = DB::table('tarif_tindakans')
            ->where($where)
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[$key]['id'] = $value->id;
            $data[$key]['text'] = $value->tindakan;
        }
        return response()->json([
            'data' => $data
        ], 200);
    }



    // select aset
    // public function optionsSelectAset(Request $request)
    // {
    //     $query = DB::table('tbl_asets')
    //         ->where('status', '=', '1')
    //         ->where('kategori', '=', 'Alkes')
    //         ->when($request->values != '', function ($q) use ($request) {
    //             $q->where('id', '=', $request->values);
    //         })
    //         ->where(function ($q) use ($request) {
    //             $search = $request->search;
    //             $q->where('nama', 'like', "%$search%")
    //                 ->orWhere('no_aset', 'like', "%$search%")
    //                 ->orWhere('no_sn', 'like', "%$search%");
    //             // Tambah kolom lain jika dibutuhkan
    //         })
    //         ->limit(5)
    //         ->get();

    //     $data = [];
    //     foreach ($query as $key => $value) {
    //         $data[$key]['id'] = $value->id;
    //         $data[$key]['text'] = $value->nama . ' - ' . $value->no_aset . ' - ' . $value->no_sn. ' - ' . $value->id_lokasi;
    //     }
    //     return response()->json([
    //         'data' => $data
    //     ], 200);
    // }


    // GlobalController.php
public function optionsSelectAset(Request $request)
{
    $search = $request->search;

    $query = DB::table('tbl_asets')
        ->leftJoin('tbl_lokasis', 'tbl_lokasis.id', '=', 'tbl_asets.id_lokasi')
        ->where('tbl_asets.status', '1')
        ->where('tbl_asets.kategori', 'Alkes')
        ->when($search, function ($q) use ($search) {
            $q->where(function ($q2) use ($search) {
                $q2->where('tbl_asets.nama', 'like', "%{$search}%")
                   ->orWhere('tbl_asets.no_aset', 'like', "%{$search}%")
                   ->orWhere('tbl_asets.no_sn', 'like', "%{$search}%");
            });
        })
        ->select(
            'tbl_asets.id',
            DB::raw("CONCAT(tbl_asets.nama, ' - ', tbl_asets.no_aset, ' - ', tbl_asets.no_sn) as text"),
            'tbl_asets.no_sn',
            'tbl_asets.id_lokasi',
            'tbl_lokasis.nama as lokasi_name'
        )
        ->limit(10)
        ->get();

    // return as array of objects (id, text, plus extra fields)
    return response()->json(['data' => $query], 200);
}



    // select lokasi
    public function optionsSelectLokasi(Request $request)
    {
        $query = DB::table('tbl_lokasis')
            ->where('status', '=', '1')
            ->when($request->values != '', function ($q) use ($request) {
                $q->where('id', '=', $request->values);
            })
            ->where(function ($q) use ($request) {
                $search = $request->search;
                $q->where('nama', 'like', "%$search%");
                // Tambah kolom lain jika dibutuhkan
            })
            ->limit(5)
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[$key]['id'] = $value->id;
            $data[$key]['text'] = $value->nama;
        }
        return response()->json([
            'data' => $data
        ], 200);
    }

    // select kondisi aset
    public function optionsSelectKondisiAset(Request $request)
    {
        $query = DB::table('tbl_kondisis')
            ->where('status', '=', '1')
            ->when($request->values != '', function ($q) use ($request) {
                $q->where('id', '=', $request->values);
            })
            ->where(function ($q) use ($request) {
                $search = $request->search;
                $q->where('nama', 'like', "%$search%");
                // Tambah kolom lain jika dibutuhkan
            })
            ->limit(5)
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[$key]['id'] = $value->id;
            $data[$key]['text'] = $value->nama;
        }
        return response()->json([
            'data' => $data
        ], 200);
    }

    // select kelompok aset
    public function optionsSelectKelompokAset(Request $request)
    {
        $query = DB::table('tbl_kelompok')
            ->where('status', '=', '1')
            ->when($request->values != '', function ($q) use ($request) {
                $q->where('id', '=', $request->values);
            })
            ->where(function ($q) use ($request) {
                $search = $request->search;
                $q->where('nama', 'like', "%$search%");
                // Tambah kolom lain jika dibutuhkan
            })
            ->limit(5)
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[$key]['id'] = $value->id;
            $data[$key]['text'] = $value->nama;
        }
        return response()->json([
            'data' => $data
        ], 200);
    }

    // select Vendors
    public function optionsSelectVendor(Request $request)
    {
        $query = DB::table('tbl_vendors')
            ->where('status', '=', '1')
            ->when($request->values != '', function ($q) use ($request) {
                $q->where('id', '=', $request->values);
            })
            ->where(function ($q) use ($request) {
                $search = $request->search;
                $q->where('nama', 'like', "%$search%");
                // Tambah kolom lain jika dibutuhkan
            })
            ->limit(5)
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[$key]['id'] = $value->id;
            $data[$key]['text'] = $value->nama;
        }
        return response()->json([
            'data' => $data
        ], 200);
    }

    // select Rolls
    public function optionsSelectRoll(Request $request)
    {
        $query = DB::table('tbl_rolls')
            ->where('status', '=', '1')
            ->when($request->values != '', function ($q) use ($request) {
                $q->where('id', '=', $request->values);
            })
            ->where(function ($q) use ($request) {
                $search = $request->search;
                $q->where('nama', 'like', "%$search%");
                // Tambah kolom lain jika dibutuhkan
            })
            ->limit(5)
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[$key]['id'] = $value->id;
            $data[$key]['text'] = $value->nama;
        }
        return response()->json([
            'data' => $data
        ], 200);
    }

}