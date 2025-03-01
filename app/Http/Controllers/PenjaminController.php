<?php

namespace App\Http\Controllers;

use App\Models\Diskon_penjamins;
use App\Models\Penjamins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjaminController extends Controller
{
      // Index
    public function index()
    {
        $data = [
            'title'        => 'Penjamin',
            'menuTitle'    => 'Master Data',
            'menuSubtitle' => 'Penjamin',
        ];
        return view('master-data.penjamin.penjamin', $data);
    }

      // Views Table
    public function views()
    {
        $query = Penjamins::all();

        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id'     => $value->id,
                'kode'   => $value->kode,
                'nama'   => $value->nama,
                'coa'    => $value->coa,
                'email'  => $value->email,
                'tarif'  => $value->tarif,
                'status' => $value->status,
                'telpon' => $value->telpon,
                'alamat' => $value->alamat,
                'margin' => $value->margin,
            ];
        }
        return response()->json($data, 200);
    }

    public function get_detail_discont($id)
    {
        $data = [
            'row_disc_rajal' => Diskon_penjamins::where([
                ['penjamin', $id],
                ['kategori', 'Rawat Jalan'],
            ])->first(),
            'row_disc_ranap' => Diskon_penjamins::where([
                ['penjamin', $id],
                ['kategori', 'Rawat Inap'],
            ])->first(),
        ];
        return response()->json($data, 200);



          // $row_disc_rajal = Diskon_penjamins::where('penjamin', $id)->where('kategori', 'Rawat Jalan')->first();
          // $data = ;
          // return response()->json($data, 200);
    }

      // Store
    public function select()
    {
        $query = DB::table('coas')
            ->where('status', '1')
            ->where('kategori', 'penjamin')
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[$key]['id']   = $value->id;
            $data[$key]['text'] = $value->kode . ' - ' . $value->nama;
        }
        return response()->json([
            'data' => $data
        ], 200);
    }


      // Store
    public function select_tarif()
    {
        $query = DB::table('sk_tarifs')
            ->where('status', '1')
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[$key]['id']   = $value->id;
            $data[$key]['text'] = $value->no_sk;
        }
        return response()->json([
            'data' => $data
        ], 200);
    }


      // Store
    public function store(Request $request)
    {
          // dd($request);
        $query = Penjamins::create([
            'kode'   => $request->kode,
            'nama'   => $request->nama,
            'coa'    => $request->coa,
            'email'  => $request->email,
            'tarif'  => $request->tarif,
            'telpon' => $request->telpon,
            'alamat' => $request->alamat,
            'margin' => $request->margin,
            'status' => $request->status == 'on' ? '1' : '0',
        ]);

        $query = Diskon_penjamins::insert(
            [
                  // RJ
                [
                    'penjamin'     => $request->kode,
                    'kategori'     => 'Rawat Jalan',
                    'tindakan'     => $request->rj_tindakan,
                    'konsultasi'   => $request->rj_konsultasi,
                    'ok'           => $request->rj_ok,
                    'cathlab'      => $request->rj_cathlab,
                    'radiologi'    => $request->rj_radiologi,
                    'laboratorium' => $request->rj_lab,
                    'akomodasi'    => $request->rj_akomodasi,
                    'sewa_alat'    => $request->rj_alat,
                    'paket'        => $request->rj_paket
                ],
                  //RI
                [
                    'penjamin'     => $request->kode,
                    'kategori'     => 'Rawat Inap',
                    'tindakan'     => $request->ri_tindakan,
                    'konsultasi'   => $request->ri_konsultasi,
                    'ok'           => $request->ri_ok,
                    'cathlab'      => $request->ri_cathlab,
                    'radiologi'    => $request->ri_radiologi,
                    'laboratorium' => $request->ri_lab,
                    'akomodasi'    => $request->ri_akomodasi,
                    'sewa_alat'    => $request->ri_alat,
                    'paket'        => $request->ri_paket
                ],
            ]
        );


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
        $query = Penjamins::where('id', $id)->update([
            'status' => $request->status,
        ]);

        if ($query) {
            return response()->json([
                'success' => true,
                'message' => 'Sukses mengubah status menjadi ' . ($request->status === '1' ? 'Aktif' : 'Tidak Aktif'),
                'data'    => [],
            ], status: 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status.',
                'data'    => [],
            ], status: 400);
        }
    }

      // Update
    public function update(Request $request, $id)
    {
        $query = Penjamins::where('id', $id)->update([
            'nama'   => $request->nama,
            'coa'    => $request->coa,
            'email'  => $request->email,
            'tarif'  => $request->tarif,
            'telpon' => $request->telpon,
            'alamat' => $request->alamat,
            'margin' => $request->margin,
        ]);
          // Update Diskon Penjamin RJ
        $query = Diskon_penjamins::where([
            'penjamin' => $request->kode,
            'kategori' => 'Rawat Jalan'
        ])->update([
            'tindakan'     => $request->rj_tindakan,
            'konsultasi'   => $request->rj_konsultasi,
            'ok'           => $request->rj_ok,
            'cathlab'      => $request->rj_cathlab,
            'radiologi'    => $request->rj_radiologi,
            'laboratorium' => $request->rj_lab,
            'akomodasi'    => $request->rj_akomodasi,
            'sewa_alat'    => $request->rj_alat,
            'paket'        => $request->rj_paket
        ]);

          // Update Diskon Penjamin RI
        $query = Diskon_penjamins::where([
            'penjamin' => $request->kode,
            'kategori' => 'Rawat Inap'
        ])->update([
            'tindakan'     => $request->ri_tindakan,
            'konsultasi'   => $request->ri_konsultasi,
            'ok'           => $request->ri_ok,
            'cathlab'      => $request->ri_cathlab,
            'radiologi'    => $request->ri_radiologi,
            'laboratorium' => $request->ri_lab,
            'akomodasi'    => $request->ri_akomodasi,
            'sewa_alat'    => $request->ri_alat,
            'paket'        => $request->ri_paket
        ]);

        if ($query) {
            return response()->json([
                'success' => true,
                'data'    => [],
                'message' => 'Data Berhasil Diubah.',
            ], status: 200);
        } else {
            return response()->json([
                'success' => false,
                'data'    => [],
                'message' => 'Data Gagal Diubah.',
            ], status: 400);
        }
    }

      // Delete
    public function destroy(Request $request, $id)
    {
        $query = Penjamins::where('id', $id)->delete();
        $query = Diskon_penjamins::where('penjamin', $request->kode)->delete();
        if ($query) {
            return response()->json([
                'success' => true,
                'data'    => [],
                'message' => 'Data Berhasil Dihapus.',
            ], status: 200);
        } else {
            return response()->json([
                'success' => false,
                'data'    => [],
                'message' => 'Data Gagal Dihapus.',
            ], status: 400);
        }
    }
}
