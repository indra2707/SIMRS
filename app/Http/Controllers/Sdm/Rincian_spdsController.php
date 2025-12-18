<?php

namespace App\Http\Controllers\sdm;

use App\Http\Controllers\Controller;
use App\Models\sdm\Rincian_spds;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as Db;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Rincian_spdsController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title' => 'Rincian SPD',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Rincian SPD',
        ];
        return view('sdm.rincian_spd.rincian_spd', $data);
    }

    // Views Table
    public function views()
    {
        $query = DB::table('tbl_spd_details')
            ->join('tbl_spds', 'tbl_spds.no_surat', '=', 'tbl_spd_details.no_surat')
            ->join('pegawai', 'pegawai.id', '=', 'tbl_spd_details.id_pegawai')
            ->join('tbl_kotas', 'tbl_kotas.id', '=', 'tbl_spds.id_kota1')
            ->join('tbl_kotas as kota2', 'kota2.id', '=', 'tbl_spds.id_kota2')
            ->select(
                'tbl_spd_details.*',
                'pegawai.nama_pekerja as nama_pegawai',
                'tbl_spds.tgl_awal',
                'tbl_spds.tgl_akhir',
                'tbl_spds.pelaksanaan',
                'tbl_spds.status as status_spd',
                'tbl_kotas.nama as nama_kota1',
                'kota2.nama as nama_kota2'
            )
            ->where('tbl_spds.status', 'Close')
            ->where('tbl_spds.status', 'Close')
            ->where(function ($q) {
                $q->where('tbl_spds.pelaksanaan', 'PD-DN')
                    ->orWhere('tbl_spds.pelaksanaan', 'PD-LN');
            })
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id' => $value->id,
                'jenis' => $value-> jenis,
                'id_menyetujui' =>$value->id_menyetujui,
                'id_mengajukan' =>$value->id_mengajukan,
                'panjar' =>$value->panjar,
                'no_surat' => $value->no_surat,
                'nama_pegawai' => $value->nama_pegawai,
                'tgl_awal' => Carbon::parse($value->tgl_awal)->format('d M Y'),
                'tgl_akhir' => Carbon::parse($value->tgl_akhir)->format('d M Y'),
                'nama_kota1' => $value->nama_kota1,
                'nama_kota2' => $value->nama_kota2,
                'status' => $value->status,
            ];
        }
        return response()->json($data, 200);
    }

    // update status
    public function updateStatus(Request $request, $id)
    {
        $query = Rincian_spds::where('id', $id)->update([
            'status' => $request->status,
            'updated_by' => Auth::user()->username,
        ]);
        if ($query) {
            return response()->json([
                'success' => true,
                'message' => 'Sukses mengubah status menjadi ' . ($request->status === 'Close' ? 'Draft' : 'Close'),
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
