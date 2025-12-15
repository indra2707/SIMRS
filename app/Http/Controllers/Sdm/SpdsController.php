<?php

namespace App\Http\Controllers\Sdm;

use App\Http\Controllers\Controller;
use App\Models\Sdm\Spds;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB as Db;

class SpdsController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title' => 'Surat Perjalanan Dinas',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Surat Perjalanan Dinas',
        ];
        return view('sdm.spd.spd', $data);
    }

    // Views Table
    public function views()
    {
        // $query = Spds::all();

        $query = Db::table('tbl_spds')
            ->join('pegawai', 'pegawai.id', '=', 'tbl_spds.id_pegawai')
            ->join('tbl_kotas', 'tbl_kotas.id', '=', 'tbl_spds.id_kota1')
            ->join('tbl_kotas as kota2', 'kota2.id', '=', 'tbl_spds.id_kota2')
            ->select(
                'tbl_spds.*',
                'pegawai.nama_pekerja as nama_pegawai',
                'tbl_kotas.nama as nama_kota1',
                'kota2.nama as nama_kota2'
            )
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id' => $value->id,
                'no_surat' => $value->no_surat,
                'id_pegawai' => $value->id_pegawai,
                'pelaksanaan' => $value->pelaksanaan,
                'nama_pegawai' => $value->nama_pegawai,
                'id_kota1' => $value -> id_kota1,
                'id_kota2' => $value -> id_kota2,
                'nama_kota1' => $value->nama_kota1,
                'nama_kota2' => $value->nama_kota2,
                'tgl_awal' => $value->tgl_awal,
                'tgl_akhir' =>$value->tgl_akhir,
                'tgl_masuk' => Carbon::parse($value->tgl_masuk)->format('d/m/Y'),
                'kendaraan' => $value->kendaraan,
                'ditanggung' => $value->ditanggung,
                'hak_cuti' => $value->hak_cuti,
                'cuti_lalu' => $value->cuti_lalu,
                'jatuh_tempo' => $value->jatuh_tempo,
                'panjar_cuti' => $value->panjar_cuti,
                'keterangan' => $value->keterangan,
                'id_pimpinan' => $value->id_pimpinan,
                'pengikut1' => ($value->pengikut == '0') ? 'Tidak Ada' : 'Ada',
                'status' => $value->status,
            ];
        }
        return response()->json($data, 200);
    }

    // Simpan
    public function store(Request $request)
    {
        $query = Spds::create([
            'no_surat' => $request->no_surat,
            'id_pegawai' => $request->id_pegawai,
            'pelaksanaan' => $request->pelaksanaan,
            'id_kota1' => $request->id_kota1,
            'id_kota2' => $request->id_kota2,
            'tgl_awal' => $request->tgl_awal,
            'tgl_akhir' => $request->tgl_akhir,
            'tgl_masuk' => Carbon::createFromFormat('d/m/Y', $request->tgl_masuk)->format('Y-m-d'),
            'kendaraan' => $request->kendaraan,
            'ditanggung' => $request->ditanggung,
            'hak_cuti' => $request->hak_cuti,
            'cuti_lalu' => $request->cuti_lalu,
            'jatuh_tempo' => $request->jatuh_tempo,
            'panjar_cuti' => $request->panjar_cuti,
            'keterangan' => $request->keterangan,
            'id_pimpinan' => $request->id_pimpinan,
            'pengikut1' => $request->pengikut,
            'status' => 'Draft'
        ]);
        if ($query) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Data Berhasil Ditambahkan.',
            ], status: 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Data Gagal Ditambahkan.',
            ], status: 400);
        }
    }

    // Update
    public function update(Request $request, $id)
    {
        $query = Spds::where('id', $id)->update([
            'no_surat' => $request->no_surat,
            'id_pegawai' => $request->id_pegawai,
            'pelaksanaan' => $request->pelaksanaan,
            'id_kota1' => $request->id_kota1,
            'id_kota2' => $request->id_kota2,
            'tgl_awal' => $request->tgl_awal,
            'tgl_akhir' => $request->tgl_akhir,
            'tgl_masuk' => Carbon::createFromFormat('d/m/Y', $request->tgl_masuk)->format('Y-m-d'),
            'kendaraan' => $request->kendaraan,
            'ditanggung' => $request->ditanggung,
            'hak_cuti' => $request->hak_cuti,
            'cuti_lalu' => $request->cuti_lalu,
            'jatuh_tempo' => $request->jatuh_tempo,
            'panjar_cuti' => $request->panjar_cuti,
            'keterangan' => $request->keterangan,
            'id_pimpinan' => $request->id_pimpinan,
            'pengikut1' => $request->pengikut,
            'status' => 'Draft'
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
        $query = Spds::where('id', $id)->delete();
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
        $query = Spds::where('id', $id)->update([
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
}
