<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MaterData\SKStruktur;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SKStrukturController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title' => 'SK Struktur',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'SK Struktur',
        ];
        return view('master-data.sk-struktur.sk-struktur', $data);
    }

    // Views Table
    public function views()
    {
        $query = SKStruktur::all();
        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id' => $value->id,
                'no_sk' => $value->no_sk,
                'tanggal_mulai' => Carbon::createFromFormat('Y-m-d', $value->tanggal_mulai)->format('d/m/Y'),
                'tanggal_selesai' => Carbon::createFromFormat('Y-m-d', $value->tanggal_selesai)->format('d/m/Y'),
                'keterangan' => $value->keterangan,
                'status' => $value->status,
            ];
        }
        return response()->json($data, 200);
    }

    // Simpan
    public function store(Request $request)
    {
        $query = SKStruktur::create([
            'no_sk' => $request->no_sk,
            'tanggal_mulai' => Carbon::createFromFormat('d/m/Y', $request->tanggal_mulai)->format('Y-m-d'),
            'tanggal_selesai' => Carbon::createFromFormat('d/m/Y', $request->tanggal_selesai)->format('Y-m-d'),
            'keterangan' => $request->keterangan,
            'status' => $request->status == 'on' ? '1' : '0',
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
        $query = SKStruktur::where('id', $id)->update([
            'no_sk' => $request->no_sk,
            'tanggal_mulai' => Carbon::createFromFormat('d/m/Y', $request->tanggal_mulai)->format('Y-m-d'),
            'tanggal_selesai' => Carbon::createFromFormat('d/m/Y', $request->tanggal_selesai)->format('Y-m-d'),
            'keterangan' => $request->keterangan,
            'status' => $request->status == 'on' ? '1' : '0',
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
        $query = SKStruktur::where('id', $id)->delete();
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
        $query = SKStruktur::where('id', $id)->update([
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
