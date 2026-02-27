<?php

namespace App\Http\Controllers\Pintu;

use App\Http\Controllers\Controller;
use App\Models\Pintu\KartuJaga;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class KartuJagaController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title' => 'Kartu Jaga',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Kartu Jaga',
        ];
        return view('pintu.kartu_jaga.kartu_jaga', $data);
    }

    // Views Table
    public function views()
    {
        $query = KartuJaga::all();
        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id' => $value->id,
                'nama_pasien' => $value->nama_pasien,
                'nama' => $value->nama,
                'no_hp' => $value->no_hp,
                'ruangan' => $value->ruangan,
                'no_kartu' => $value->no_kartu,
                'deposit' => 'Rp ' . number_format($value->deposit, 0, '.', ','),
                'created_by' => $value->created_by,
                'updated_by' => $value->updated_by,
                'status' => $value->status,
                'created_at' => $value->created_at ? $value->created_at->format('Y-m-d H:i:s') : null,
                'updated_at' => $value->updated_at ? $value->updated_at->format('Y-m-d H:i:s') : null,
            ];
        }
        return response()->json($data, 200);
    }

    // Simpan
    public function store(Request $request)
    {
        $query = KartuJaga::create([
            'nama' => $request->nama,
            'nama_pasien' => $request->nama_pasien,
            'no_hp' => preg_replace('/^\+62/', '0', str_replace(' ', '', $request->no_hp)),
            'ruangan' => $request->ruangan,
            'no_kartu' => $request->no_kartu,
            'deposit' => preg_replace('/[^0-9]/', '', $request->deposit),
            'created_by' => Session::get('nama_pekerja'),
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
        $query = KartuJaga::where('id', $id)->update([
            'nama' => $request->nama,
            'nama_pasien' => $request->nama_pasien,
            'no_hp' => preg_replace('/^\+62/', '0', str_replace(' ', '', $request->no_hp)),
            'ruangan' => $request->ruangan,
            'no_kartu' => $request->no_kartu,
            'deposit' => preg_replace('/[^0-9]/', '', $request->deposit),
            'updated_by' => Session::get('nama_pekerja'),
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
        $query = KartuJaga::where('id', $id)->delete();
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


    //update status
    public function updateStatus(Request $request, $id)
    {
        $query = KartuJaga::where('id', $id)->update([
            'status' => 0,
            'updated_by' => Session::get('nama_pekerja'),
        ]);
        if ($query) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Status Berhasil Diubah.',
            ], status: 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Status Gagal Diubah.',
            ], status: 400);
        }
    }
}