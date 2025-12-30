<?php

namespace App\Http\Controllers\sdm;

use App\Http\Controllers\Controller;
use App\Models\sdm\Rincian_spds;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as Db;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Dompdf\Dompdf;
use Dompdf\Options;

class Rincian_spdsController extends Controller
{
    //Index
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
                'pegawai.golongan_upah as golongan_upah',
                'tbl_spds.tgl_akhir',
                'tbl_spds.pelaksanaan',
                'tbl_spds.status as status_spd',
                'tbl_kotas.nama as nama_kota1',
                'kota2.nama as nama_kota2'
            )
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
                'jenis' => $value->jenis,
                'id_pegawai' => $value->id_pegawai,
                'id_menyetujui' => $value->id_menyetujui,
                'golongan_upah' => $value->golongan_upah,
                'id_mengajukan' => $value->id_mengajukan,
                'panjar' => $value->panjar,
                'no_surat' => $value->no_surat,
                'nama_pegawai' => $value->nama_pegawai,
                'tgl_awal' => Carbon::parse($value->tgl_awal)->format('d M Y'),
                'tgl_akhir' => Carbon::parse($value->tgl_akhir)->format('d M Y'),
                'nama_kota1' => $value->nama_kota1,
                'nama_kota2' => $value->nama_kota2,
                'status' => $value->status,
                'tanggal' => Carbon::parse($value->tanggal)->format('d/m/Y'),
            ];
        }
        return response()->json($data, 200);
    }

    //view detail
    public function views_detail(Request $request)
    {
        $data = DB::table('tbl_rincian_spd')
            ->join('tbl_biaya_spd', 'tbl_biaya_spd.id', '=', 'tbl_rincian_spd.id_biaya')

            ->select(
                'tbl_rincian_spd.*',
                'tbl_biaya_spd.nama as nama_biaya',
                'tbl_rincian_spd.id as id_detail'
            )

            ->where('id_pegawai', $request->id_pegawai)
            ->where('no_surat', $request->no_surat)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    //simpan spd detail
    public function store(Request $request)
    {
        $query = DB::table('tbl_rincian_spd')->insert([
            'id_biaya' => $request->biaya,
            'no_surat' => $request->no_surat,
            'id_pegawai' => $request->id_pegawai,
            'harga' => (int) preg_replace('/[^0-9]/', '', $request->harga),
            'jumlah' => $request->jumlah,
            'created_by' => Auth::user()->username,
            'created_at' => now(),
        ]);

        if ($query) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Data Berhasil Ditambahkan.',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'data' => [],
            'message' => 'Data Gagal Ditambahkan.',
        ], 400);
    }

    //update spd detail
    public function update_detail(Request $request, $id)
    {
        $query = DB::table('tbl_rincian_spd')
            ->where('id', $id)
            ->update([
                'id_biaya' => $request->biaya,
                'no_surat' => $request->no_surat,
                'id_pegawai' => $request->id_pegawai,
                'harga' => (int) preg_replace('/[^0-9]/', '', $request->harga),
                'jumlah' => $request->jumlah,
                'updated_by' => Auth::user()->username,
                'updated_at' => now(),
            ]);

        if ($query) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Data Berhasil Ditambahkan.',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'data' => [],
            'message' => 'Data Gagal Ditambahkan.',
        ], 400);
    }

    // Update
    public function update(Request $request, $id)
    {
        $query = Rincian_spds::where('id', $id)->update([
            'id_mengajukan' => $request->id_mengajukan,
            'id_menyetujui' => $request->id_menyetujui,
            'jenis' => $request->jenis,
            'tanggal' => Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('Y-m-d'),
            'panjar' => (int) preg_replace('/[^\d]/', '', $request->panjar),
            'created_by' => Auth::user()->username,
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

    // Mengambil data rincian biaya
    public function getDetailRincianbiaya($id)
    {
        $biaya = DB::table('tbl_biaya_spd')
            ->where('id', $id)
            ->first();

        if (!$biaya) {
            return response()->json([
                'error' => 'Data rincian biaya tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'harga' => number_format($biaya->harga_utama, 0, ',', '.'), // Mengambil harga
        ], 200);
    }

    //hapus detail
    public function destroy($id)
    {
        $query = DB::table('tbl_rincian_spd')
            ->where('id', $id)
            ->delete();

        if ($query) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Data Berhasil Dihapus.',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'data' => [],
            'message' => 'Data Gagal Dihapus.',
        ], 400);
    }

    // Print
    public function print(Request $request, $id)
    {
        $rincian = DB::table('tbl_spd_details as d')
            ->join('tbl_spds as s', 's.no_surat', '=', 'd.no_surat')

            // pegawai utama
            ->join('pegawai as p', 'p.id', '=', 'd.id_pegawai')
            ->join('tbl_jabatan as j', 'j.id', '=', 'p.id_jabatan')

            // mengajukan
            ->join('pegawai as pm', 'pm.id', '=', 'd.id_mengajukan')
            ->join('tbl_jabatan as jm', 'jm.id', '=', 'pm.id_jabatan')

            // menyetujui
            ->join('pegawai as ps', 'ps.id', '=', 'd.id_menyetujui')
            ->join('tbl_jabatan as js', 'js.id', '=', 'ps.id_jabatan')

            // kota
            ->join('tbl_kotas as k1', 'k1.id', '=', 's.id_kota1')
            ->join('tbl_kotas as k2', 'k2.id', '=', 's.id_kota2')

            ->select(
                'd.*',

                // pegawai
                'p.nama_pekerja as nama_pegawai',
                'p.nomor_pekerja',
                'j.nama_jabatan as jabatan_pegawai',

                // spd
                's.tgl_awal',
                's.tgl_akhir',
                's.pelaksanaan',
                's.status as status_spd',

                // kota
                'k1.nama as nama_kota1',
                'k2.nama as nama_kota2',

                // mengajukan
                'pm.nama_pekerja as nama_mengajukan',
                'jm.nama_jabatan as jabatan_mengajukan',

                // menyetujui
                'ps.nama_pekerja as nama_menyetujui',
                'js.nama_jabatan as jabatan_menyetujui'
            )
            ->where('d.id', $id)
            ->where('d.no_surat', $request->no_surat)
            ->where('d.id_pegawai', $request->id_pegawai)
            ->first();

        if (!$rincian) {
            abort(404, 'SPD tidak ditemukan.');
        }

        // DATA RINCIAN BIAYA (MULTI DATA)
        $details = DB::table('tbl_rincian_spd')
            ->join('tbl_biaya_spd', 'tbl_biaya_spd.id', '=', 'tbl_rincian_spd.id_biaya')
            ->where('tbl_rincian_spd.id_pegawai', $rincian->id_pegawai)
            ->where('tbl_rincian_spd.no_surat', $rincian->no_surat)
            ->select(
                'tbl_rincian_spd.*',
                'tbl_biaya_spd.nama as nama_biaya'
            )
            ->get();

        // RENDER VIEW KE HTML
        $html = view('sdm.rincian_spd.print', [
            'title' => 'Print Preview SPD',
            'rincian' => $rincian,
            'details' => $details,
        ])->render();

        // DOMPDF
        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'SPD-' . $rincian->no_surat . '.pdf';

        return $dompdf->stream($filename, [
            'Attachment' => false, // preview di browser
        ]);
    }

}
