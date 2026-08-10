<?php

namespace App\Http\Controllers\Surat;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Surat\AprovalDetail;
use App\Http\Controllers\Controller;

class AprovalDetailController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Aproval Detail',
            'menuTitle' => 'Aproval',
            'menuSubtitle' => 'Aproval Detail',
        ];
        return view('surat.aproval.aproval', $data);
    }

    // View
    public function views(Request $request)
    {
        $query = AprovalDetail::query()
            ->leftJoin('pegawai', 'pegawai.id', '=', 'tbl_aproval_detail.id_pegawai')
            ->orderBy('tbl_aproval_detail.parent_jabatan', 'asc')
            ->select(
                'tbl_aproval_detail.id',
                'tbl_aproval_detail.parent_jabatan',
                'tbl_aproval_detail.id_pegawai',
                'tbl_aproval_detail.id_aproval',
                'pegawai.nama_pekerja'
            );

        // Filter berdasarkan id_aproval
        if ($request->filled('id_aproval')) {
            $query->where('tbl_aproval_detail.id_aproval', $request->id_aproval);
        }

        $data = [];

        foreach ($query->get() as $value) {
            $data[] = [
                'id_detail' => $value->id,
                'parent_jabatan' => $value->parent_jabatan,
                'id_pegawai' => $value->id_pegawai,
                'nama_pekerja' => $value->nama_pekerja,
                'id_aproval' => $value->id_aproval,
            ];
        }

        return response()->json($data, 200);
    }


    // Simpan
    public function store(Request $request)
    {
        $query = AprovalDetail::create([
            'id_aproval' => $request->id_aproval,
            'parent_jabatan' => $request->parent_jabatan,
            'id_pegawai' => $request->id_pegawai,
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
        $aproval = AprovalDetail::find($id);
        $aproval->update([
            'id_aproval' => $request->id_aproval,
            'parent_jabatan' => $request->parent_jabatan,
            'id_pegawai' => $request->id_pegawai,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Diupdate.',
        ], 200);
    }


    // Delete
    public function destroy($id)
    {
        $aproval = AprovalDetail::find($id);

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
}