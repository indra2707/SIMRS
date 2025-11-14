<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MaterData\Kalibrasis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KalibrasiController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title' => 'Kalibrasi',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Kalibrasi',
        ];
        return view('master-data.kalibrasi.kalibrasi', $data);
    }

    // Views Table
    public function views()
    {

        $query = Db::table('tbl_kalibrasi')
            ->join('tbl_asets', 'tbl_asets.id', '=', 'tbl_kalibrasi.id_aset')
            ->join('tbl_lokasis', 'tbl_asets.id_lokasi', '=', 'tbl_lokasis.id')
            ->select('tbl_kalibrasi.*', 'tbl_asets.no_aset as no_aset', 'tbl_asets.nama as nama_aset', 'tbl_asets.merek as merek', 'tbl_asets.no_sn as no_sn', 'tbl_lokasis.nama as nama_lokasi')
            ->where('aktif', '1')
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $currentDate    = Carbon::now(); // Mendapatkan tanggal sekarang
            $tglKalibrasi   = Carbon::createFromFormat('Y-m-d', $value->tgl_kalibrasi); // Mengonversi tgl_kalibrasi ke Carbon object
            $diffInDays     = $currentDate->diffInDays($tglKalibrasi); // Menghitung selisih hari
            $diffInDays = $currentDate->gt($tglKalibrasi) ? -$diffInDays : $diffInDays;  // Menghitung selisih hari dengan mempertimbangkan tanggal yang lebih besar

            $data[] = [
                'id'                => $value->id,
                'id_aset'           => $value->id_aset,
                'no_aset'           => $value->no_aset,
                'nama_aset'         => $value->nama_aset,
                'merek'             => $value->merek,
                'no_sn'             => $value->no_sn,
                'tgl_kalibrasi'     => convertYmdToDmy($value->tgl_kalibrasi),
                'nama_lokasi'       => $value->nama_lokasi,
                'status'            => $value->status,
                'aktif'             => $value->aktif,
                'selisih_hari'      => $diffInDays, // Menambahkan selisih hari ke dalam data
            ];
        }
        return response()->json($data, 200);
    }

   // Store
public function store(Request $request)
{
    // Ubah nilai aktif menjadi 1 atau 0
    $aktif = $request->aktif == 'on' ? '1' : '0';

    // 🔍 Cek apakah sudah ada data dengan kode_aset yang sama dan aktif = 1
    $cekAktif = Kalibrasis::where('id_aset', $request->kode_aset)
        ->where('aktif', '1')
        ->exists();

    // Jika ditemukan data aktif untuk aset yang sama → tolak simpan
    if ($cekAktif && $aktif == '1') {
        return response()->json([
            'success' => false,
            'data' => [],
            'message' => 'Data gagal disimpan karena aset ini sudah memiliki data dengan status aktif.',
        ], 400);
    }

    // 🚀 Simpan data baru jika lolos validasi
    $query = Kalibrasis::create([
        'id_aset'       => $request->kode_aset,
        'tgl_kalibrasi' => convertDmyToYmd($request->tgl_kalibrasi),
        'status'        => $request->status,
        'aktif'         => $aktif,
    ]);

    if ($query) {
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
        $query = Kalibrasis::where('id', $id)->update([
            'id_aset'       => $request->kode_aset,
            'tgl_kalibrasi' => convertDmyToYmd($request->tgl_kalibrasi),
            'status'        => $request->status,
            'aktif'         => $request->aktif == 'on' ? '1' : '0',
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
        $query = Kalibrasis::where('id', $id)->delete();
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
        $query = Kalibrasis::where('id', $id)->update([
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
}
