<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MaterData\Jadwal_dokters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Jadwal_dokterController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title' => 'Jadwal Dokter',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Jadwal Dokter',
        ];
        return view('master-data.jadwal-dokter.jadwal-dokter', $data);
    }

    // Views Table
    public function views()
    {
        // $query = Jadwal_dokters::all();

        $query = Db::table('Jadwal_dokters')
            ->join('polis', 'polis.id', '=', 'Jadwal_dokters.kode_poli')
            ->join('petugas', 'petugas.id', '=', 'jadwal_dokters.kode_dokter')
            ->join('spesialisses', 'spesialisses.id', '=', 'petugas.kode_spesialis')
            ->select('Jadwal_dokters.*', 'polis.nama as nama_poli', 'petugas.nama as nama_dokter', 'spesialisses.nama as nama_spesialis')
            ->get();


        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id'                => $value->id,
                'kode_poli'         => $value->kode_poli,
                'nama_poli'         => $value->nama_poli,
                'nama_spesialis'    => $value->nama_spesialis,
                'hari'              => $value->hari,
                'kode_dokter'       => $value->kode_dokter,
                'nama_dokter'       => $value->nama_dokter,
                'status'            => $value->status,
                'mulai'             => $value->mulai,
                'akhir'             => $value->akhir,
                'jam'               => date('G:i',strtotime($value->mulai)).' - '.date('G:i',strtotime($value->akhir)),
                'estimasi'          => $value->estimasi,
                'kouta'             => $value->kouta,
            ];
        }
        return response()->json($data, 200);
    }


    // Store
    public function store(Request $request)
    {
        $query = Jadwal_dokters::create([
            'hari' => $request->hari,
            'kode_poli' => $request->kode_poli,
            'kode_dokter' => $request->kode_dokter,
            'mulai' => $request->mulai,
            'akhir' => $request->akhir,
            'estimasi' => $request->estimasi,
            'kouta' => $request->kouta,
            // 'status' => $request->status == 'on' ? '1' : '0',
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

    // update status check
    public function updateStatus(Request $request, $id)
    {
        $query = Jadwal_dokters::where('id', $id)->update([
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
        $query = Jadwal_dokters::where('id', $id)->update([
            'kode_poli' => $request->kode_poli,
            'kode_dokter' => $request->kode_dokter,
            'hari' => $request->hari,
            'mulai' => $request->mulai,
            'akhir' => $request->akhir,
            'estimasi' => $request->estimasi,
            'kouta' => $request->kouta,
            // 'status' => $request->status == 'on' ? '1' : '0',
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
        $query = Jadwal_dokters::where('id', $id)->delete();
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
}
