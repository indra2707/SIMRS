<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MaterData\Asets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Dompdf\Dompdf;


class AsetController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title' => 'Asset',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Asset',
        ];
        return view('master-data.aset.aset', $data);
    }

    // Views Table
    public function views()
    {
        // $query = Asets::all();
        $query = Db::table('tbl_asets')
            ->join('tbl_lokasis', 'tbl_lokasis.id', '=', 'tbl_asets.id_lokasi')
            ->join('tbl_kondisis', 'tbl_kondisis.id', '=', 'tbl_asets.id_kondisi')
            ->join('tbl_kelompok', 'tbl_kelompok.id', '=', 'tbl_asets.id_kelompok')
            ->join('tbl_vendors', 'tbl_vendors.id', '=', 'tbl_asets.id_vendor')
            ->select(
                'tbl_asets.*',
                'tbl_lokasis.nama as nama_lokasi',
                'tbl_kondisis.nama as nama_kondisi',
                'tbl_kelompok.nama as nama_kelompok',
                'tbl_kelompok.bulan as kelompok_bulan',
                'tbl_vendors.nama as nama_vendor'
            )
            ->get();

        $data = [];
        foreach ($query as $value) {
            $tanggal = Carbon::createFromFormat('Y-m-d', $value->tahun);
            $selisih_bulan = $tanggal->diffInMonths(now());

            // if ($value->kelompok_bulan != 0) {
            //     $penyusutan_per_bulan = $value->harga / $value->kelompok_bulan;
            // } else {
            //     $penyusutan_per_bulan = 0;
            // }

            $kelompok_bulan = (float) ($value->kelompok_bulan ?? 0);
            $harga = (float) ($value->harga ?? 0);

            $penyusutan_per_bulan = $kelompok_bulan > 0 ? $harga / $kelompok_bulan : 0;

            $sisa_umur = max($value->kelompok_bulan - $selisih_bulan, 0);
            $penyusutan = ($sisa_umur <= 0) ? 0 : $penyusutan_per_bulan;

            if ($value->kelompok_bulan > $selisih_bulan) {
                $akumulasi_penyusutan = $selisih_bulan * $penyusutan_per_bulan;
                $nilai_buku = $value->harga - $akumulasi_penyusutan;
            } else {
                $akumulasi_penyusutan = 0;
                $nilai_buku = 0;
            }


            $data[] = [
                'id' => $value->id,
                'no_aset' => $value->no_aset,
                'jenis' => $value->jenis,
                'kategori' => $value->kategori,
                'nama' => $value->nama,
                'merek' => $value->merek,
                'tipe' => $value->tipe,
                'tahun' => $tanggal->format('d/m/Y'),
                'harga' => number_format($value->harga, 0, '.', ','),
                'status' => $value->status,
                'id_kelompok' => $value->id_kelompok,
                'nama_kelompok' => $value->nama_kelompok,
                'no_sn' => $value->no_sn,
                'id_lokasi' => $value->id_lokasi,
                'id_vendor' => $value->id_vendor,
                'nama_lokasi' => $value->nama_lokasi,
                'id_kondisi' => $value->id_kondisi,
                'nama_kondisi' => $value->nama_kondisi,
                'selisih_bulan' => $selisih_bulan,
                'kelompok_bulan' => $value->kelompok_bulan,
                'sisa_umur' => $sisa_umur,
                'penyusutan' => number_format($penyusutan, 0, '.', ','),
                'nama_vendor' => $value->nama_vendor,
                'akumulasi_penyusutan' => number_format($akumulasi_penyusutan, 0, '.', ','),
                'nilai_buku' => number_format($nilai_buku, 0, '.', ','),
            ];
        }
        return response()->json([
            'total' => count($data),
            'rows' => $data
        ]);
    }


    public function views_mutasi(Request $request)
    {
        $query = DB::table('tbl_mutasis')
            ->join('tbl_asets', 'tbl_asets.id', '=', 'tbl_mutasis.id_aset')
            ->join('tbl_lokasis', 'tbl_lokasis.id', '=', 'tbl_mutasis.id_lokasi')
            ->join('tbl_lokasis as tbl_lokasis_new', 'tbl_lokasis_new.id', '=', 'tbl_mutasis.id_lokasi_new')
            ->join('tbl_kondisis', 'tbl_kondisis.id', '=', 'tbl_mutasis.id_kondisi')
            ->select(
                'tbl_mutasis.*',
                'tbl_asets.no_aset as no_aset',
                'tbl_asets.nama as nama_aset',
                'tbl_asets.no_sn as no_sn',
                'tbl_kondisis.nama as nama_kondisi',
                'tbl_lokasis.nama as nama_lokasi_asal',
                'tbl_lokasis_new.nama as nama_lokasi_tujuan'
            )
            ->where('tbl_mutasis.id_aset', $request->id)
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'nama_aset' => $value->nama_aset,
                'no_sn' => $value->no_sn,
                'tgl_mutasi' => convertYmdToDmy($value->tgl_mutasi),
                'nama_kondisi' => $value->nama_kondisi,
                'nama_lokasi_asal' => $value->nama_lokasi_asal,
                'nama_lokasi_tujuan' => $value->nama_lokasi_tujuan,
                'keterangan' => $value->keterangan,
            ];
        }
        return response()->json($data, 200);
    }

    // Simpan
    public function store(Request $request)
    {
        $query = Asets::create([
            'no_aset' => $request->no_aset,
            'jenis' => $request->jenis,
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'merek' => $request->merek,
            'tipe' => $request->tipe,
            'tahun' => Carbon::createFromFormat('d/m/Y', $request->tahun)->format('Y-m-d'),
            'harga' => floatval(str_replace(',', '', str_replace('Rp ', '', $request->harga))),
            'no_sn' => $request->no_sn,
            'status' => $request->status == 'on' ? '1' : '0',
            'id_kelompok' => $request->kode_kelompok_aset,
            'id_lokasi' => $request->kode_lokasi,
            'id_kondisi' => $request->kode_kondisi_aset,
            'id_vendor' => $request->id_vendor,
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
        $query = Asets::where('id', $id)->update([
            'no_aset' => $request->no_aset,
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'merek' => $request->merek,
            'tipe' => $request->tipe,
            'tahun' => Carbon::createFromFormat('d/m/Y', $request->tahun)->format('Y-m-d'),
            'harga' => floatval(str_replace(',', '', str_replace('Rp ', '', $request->harga))),
            'no_sn' => $request->no_sn,
            'status' => $request->status == 'on' ? '1' : '0',
            'id_lokasi' => $request->kode_lokasi,
            'id_kondisi' => $request->kode_kondisi_aset,
            'id_kelompok' => $request->kode_kelompok_aset,
            'id_vendor' => $request->id_vendor,
            'jenis' => $request->jenis,
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
        $query = Asets::where('id', $id)->delete();
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
        $query = Asets::where('id', $id)->update([
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

    public function print($id)
    {
        // $aset = Asets::find($id);

        $aset = Asets::query()
            ->join('tbl_lokasis', 'tbl_lokasis.id', '=', 'tbl_asets.id_lokasi')
            ->join('tbl_kondisis', 'tbl_kondisis.id', '=', 'tbl_asets.id_kondisi')
            ->join('tbl_kelompok', 'tbl_kelompok.id', '=', 'tbl_asets.id_kelompok')
            ->join('tbl_vendors', 'tbl_vendors.id', '=', 'tbl_asets.id_vendor')
            ->select(
                'tbl_asets.*',
                'tbl_lokasis.nama as nama_lokasi',
                'tbl_kondisis.nama as nama_kondisi',
                'tbl_kelompok.nama as nama_kelompok',
                'tbl_kelompok.bulan as kelompok_bulan',
                'tbl_vendors.nama as nama_vendor'
            )
            ->where('tbl_asets.id', $id)
            ->first();

        if (!$aset) {
            return abort(404, 'Aset tidak ditemukan.');
        }

        $data = [
            'title' => 'Print Preview Aset',
            'aset' => $aset
        ];

        $html = view('master-data.aset.print', $data)->render();

        $width = 5.5 * 28.3464567;   // 170 pt
        $height = 10 * 28.3464567; // 283 pt

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);

        // Custom paper size (10 cm × 6 cm)
        $dompdf->setPaper([0, 0, $height, $width], 'portrait');

        $dompdf->render();

        return $dompdf->stream($aset->no_aset . '.pdf', [
            "Attachment" => false
        ]);
    }

    //print all aset
    public function printAll(Request $request)
    {
        $ids = explode(',', $request->ids);

        $asets = Asets::query()
            ->join('tbl_lokasis', 'tbl_lokasis.id', '=', 'tbl_asets.id_lokasi')
            ->join('tbl_kondisis', 'tbl_kondisis.id', '=', 'tbl_asets.id_kondisi')
            ->join('tbl_kelompok', 'tbl_kelompok.id', '=', 'tbl_asets.id_kelompok')
            ->join('tbl_vendors', 'tbl_vendors.id', '=', 'tbl_asets.id_vendor')
            ->whereIn('tbl_asets.id', $ids)
            ->select(
                'tbl_asets.*',
                'tbl_lokasis.nama as nama_lokasi',
                'tbl_kondisis.nama as nama_kondisi',
                'tbl_kelompok.nama as nama_kelompok',
                'tbl_kelompok.bulan as kelompok_bulan',
                'tbl_vendors.nama as nama_vendor'
            )
            ->get();

        $html = view('master-data.aset.print-all', compact('asets'))->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('preview-aset-terpilih.pdf', [
            "Attachment" => false
        ]);
    }

}
