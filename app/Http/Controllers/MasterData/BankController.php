<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MaterData\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title' => 'Bank',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Bank',
        ];
        return view('master-data.bank.bank', $data);
    }

    // Views Table
    public function views()
    {
        $query = Bank::all();
        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id'        => $value->id,
                'nama_bank' => $value->nama_bank,
                'singkatan' => $value->singkatan,
                'jenis_bank'=> $value->jenis_bank,
                'status'    => $value->status,
            ];
        }
        return response()->json($data, 200);
    }

    // Simpan
    public function store(Request $request)
    {
        $query = Bank::create([
            'nama_bank' => $request->nama_bank,
            'singkatan' => $request->singkatan,
            'jenis_bank'=> $request->jenis_bank,
            'status'    => $request->status == 'on' ? '1' : '0',
        ]);
        if ($query) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Data Berhasil Ditambahkan.',
            ], status: 200);
        }else{
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
        $query = Bank::where('id', $id)->update([
            'nama_bank' => $request->nama_bank,
            'singkatan' => $request->singkatan,
            'jenis_bank'=> $request->jenis_bank,
            'status'    => $request->status == 'on' ? '1' : '0',
        ]);
        if ($query) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Data Berhasil Diubah.',
            ], status: 200);
        }else{
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
        $query = Bank::where('id', $id)->delete();
        if ($query) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Data Berhasil Dihapus.',
            ], status: 200);
        }else{
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
        $query = Bank::where('id', $id)->update([
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
