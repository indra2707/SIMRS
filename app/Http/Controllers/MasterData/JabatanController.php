<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MaterData\Jabatan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title' => 'Jabatan',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Jabatan',
        ];
        return view('master-data.jabatan.jabatan', $data);
    }

    // Views Table Jabatan
    public function views(Request $request)
    {
        $query = DB::table('tbl_jabatan')
            ->join('tbl_sk_struktur', 'tbl_sk_struktur.id', '=', 'tbl_jabatan.id_sk_struktur')
            ->where('tbl_jabatan.id_sk_struktur', $request->id_sk_struktur)
            ->select(
                'tbl_jabatan.id',
                'tbl_jabatan.id_sk_struktur',
                'tbl_jabatan.unit',
                'tbl_jabatan.nama_jabatan',
                'tbl_jabatan.status',
                'tbl_sk_struktur.no_sk as no_skstruktur'
            )
            ->get();

        $data = [];
        foreach ($query as $value) {
            $data[] = [
                'id' => $value->id,
                'id_sk_struktur' => $value->id_sk_struktur,
                'no_skstruktur' => $value->no_skstruktur,
                'unit' => $value->unit,
                'nama_jabatan' => $value->nama_jabatan,
                'status' => $value->status,
            ];
        }

        return response()->json($data, 200);
    }


    // Simpan SK Struktur
    public function store(Request $request)
    {
        $query = Jabatan::create([
            'id_sk_struktur' => $request->id_sk_struktur,
            'unit' => $request->unit,
            'nama_jabatan' => $request->nama_jabatan,
            'status_jabatan' => $request->status_jabatan == 'on' ? '1' : '0',
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
        $query = Jabatan::where('id', $id)->update([
            'id_sk_struktur' => $request->id_sk_struktur,
            'unit' => $request->unit,
            'nama_jabatan' => $request->nama_jabatan,
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
        $query = Jabatan::where('id', $id)->delete();
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
        $query = Jabatan::where('id', $id)->update([
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
