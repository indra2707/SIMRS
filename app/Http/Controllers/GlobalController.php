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
                        ->orWhere('tbl_asets.no_aset', 'like', "%{$search}%");
                    // ->orWhere('tbl_asets.no_sn', 'like', "%{$search}%");
                });
            })
            ->select(
                'tbl_asets.id',
                DB::raw("CONCAT(tbl_asets.no_aset, ' - ', tbl_asets.nama) as text"),
                'tbl_asets.no_sn',
                'tbl_asets.nama',
                'tbl_asets.no_sn',
                'tbl_asets.id_lokasi',
                'tbl_lokasis.nama as lokasi_name'
            )
            ->limit(10)
            ->get();

        // return as array of objects (id, text, plus extra fields)
        return response()->json(['data' => $query], 200);
    }

    // select Biaya SPD
    public function optionsSelectBiaya(Request $request)
    {
        $query = DB::table('tbl_biaya_spd')
            ->where('status', '1')

            ->when($request->search, function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%');
            })

            ->limit(10)
            ->get();

        $data = [];

        foreach ($query as $value) {

            // mapping harga sesuai golongan_upah
            switch ($request->golongan_upah) {
                case 'Biasa':
                    $harga = $value->harga_biasa;
                    break;

                case 'Madya':
                    $harga = $value->harga_madya;
                    break;

                case 'Utama':
                    $harga = $value->harga_utama;
                    break;

                default:
                    $harga = 0;
            }

            $data[] = [
                'id' => $value->id,
                'text' => $value->nama,
                'harga' => 'Rp '  .number_format($harga, 0, '.', ',')
            ];
        }

        return response()->json([
            'data' => $data
        ]);
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
            ->limit(10)
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

    // select kota
    public function optionsSelectKota(Request $request)
    {
        $query = DB::table('tbl_kotas')
            ->where('status', '=', '1')
            ->when($request->values != '', function ($q) use ($request) {
                $q->where('id', '=', $request->values);
            })
            ->where(function ($q) use ($request) {
                $search = $request->search;
                $q->where('nama', 'like', "%$search%");
                // Tambah kolom lain jika dibutuhkan
            })
            ->limit(10)
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

    // select Pegawai
    public function optionsSelectPegawai(Request $request)
    {
        $search = $request->search ?? '';
        $values = $request->values ?? '';

        $query = DB::table('pegawai')
            // filter by selected value (jika ada)
            ->when(!empty($values), function ($q) use ($values) {
                $q->where('id', $values);
            })
            // search by nama_pekerja
            ->when(!empty($search), function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('nama_pekerja', 'like', "%{$search}%");
                    $q2->orWhere('nomor_pekerja', 'like', "%{$search}%");
                });
            })
            ->limit(20)
            ->get();

        // format untuk Select2
        $data = [];
        foreach ($query as $item) {
            $data[] = [
                'id' => $item->id,
                'text' => $item->nomor_pekerja . ' - ' . $item->nama_pekerja,  // kolom yang benar
            ];
        }

        return response()->json(['data' => $data], 200);
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

    // select Bank
    public function optionsSelectBank(Request $request)
    {
        $query = DB::table('tbl_bank')
            ->where('status', '=', '1')
            ->when($request->values != '', function ($q) use ($request) {
                $q->where('id', '=', $request->values);
            })
            ->where(function ($q) use ($request) {
                $search = $request->search;
                $q->where('nama_bank', 'like', "%$search%");
                // Tambah kolom lain jika dibutuhkan
            })
            ->limit(10)
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[$key]['id'] = $value->id;
            $data[$key]['text'] = $value->nama_bank;
        }
        return response()->json([
            'data' => $data
        ], 200);
    }


    // select Fungsi
    public function optionsSelectFungsi(Request $request)
    {
        $query = DB::table('tbl_fungsi')
            ->where('status', '=', '1')
            ->when($request->values != '', function ($q) use ($request) {
                $q->where('id', '=', $request->values);
            })
            ->where(function ($q) use ($request) {
                $search = $request->search;
                $q->where('nama_fungsi', 'like', "%$search%");
                // Tambah kolom lain jika dibutuhkan
            })
            ->limit(10)
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[$key]['id'] = $value->id;
            $data[$key]['text'] = $value->nama_fungsi;
        }
        return response()->json([
            'data' => $data
        ], 200);
    }

    // select SK Struktur
    public function optionsSelectSKStruktur(Request $request)
    {
        $query = DB::table('tbl_sk_struktur')
            ->where('status', '=', '1')
            ->when($request->values != '', function ($q) use ($request) {
                $q->where('id', '=', $request->values);
            })
            ->where(function ($q) use ($request) {
                $search = $request->search;
                $q->where('no_sk', 'like', "%$search%");
                // Tambah kolom lain jika dibutuhkan
            })
            ->limit(10)
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[$key]['id'] = $value->id;
            $data[$key]['text'] = $value->no_sk;
        }
        return response()->json([
            'data' => $data
        ], 200);
    }

    // select Jabatan
    public function optionsSelectJabatan(Request $request)
    {
        // Jika id_sk_struktur kosong, kembalikan data kosong
        if (!$request->filled('id_sk_struktur')) {
            return response()->json([
                'data' => []
            ], 200);
        }

        $query = DB::table('tbl_jabatan')
            ->where('status', '1')
            ->where('id_sk_struktur', $request->id_sk_struktur)
            ->when($request->values, function ($q) use ($request) {
                $q->where('id', $request->values);
            })
            ->when($request->search, function ($q) use ($request) {
                $q->where('nama_jabatan', 'like', '%' . $request->search . '%');
            })
            ->limit(10)
            ->get();

        $data = [];
        foreach ($query as $value) {
            $data[] = [
                'id' => $value->id,
                'text' => $value->nama_jabatan
            ];
        }

        return response()->json([
            'data' => $data
        ], 200);
    }



}
