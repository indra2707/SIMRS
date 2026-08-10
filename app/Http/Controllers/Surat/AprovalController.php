<?php

namespace App\Http\Controllers\Surat;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Surat\Aproval;
use App\Http\Controllers\Controller;

class AprovalController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Aproval',
            'menuTitle' => 'Surat',
            'menuSubtitle' => 'Aproval',
        ];
        return view('surat.aproval.aproval', $data);
    }

    // View
    public function views(Request $request)
    {
        $query = Aproval::query();
        foreach ($query->get() as $value) {
            $data[] = [
                'id' => $value->id,
                'nama_aproval' => $value->nama_aproval,
                'status' => $value->status,
            ];
        }
        return response()->json($data, 200);
    }

   
    // Simpan
    public function store(Request $request)
    {
        $query = Aproval::create([
            'nama_aproval' => $request->nama_aproval
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

    //Update
    public function update(Request $request, $id)
    {
        $aproval = Aproval::find($id);
        $aproval->update([
            'nama_aproval' => $request->nama_aproval,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Diupdate.',
        ], 200);
    }


    // Delete
    public function destroy($id)
    {
        $aproval = Aproval::find($id);

        if (!$aproval) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $aproval->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus.'
        ], 200);
    }

    // Update Status
    public function updateStatus(Request $request, $id)
    {
        $query = Aproval::where('id', $id)->update([
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
