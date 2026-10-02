<?php

namespace App\Http\Controllers\Surat;

use App\Http\Controllers\Controller;
use App\Models\Surat\DisposisiMaster;
use Illuminate\Http\Request;

class DisposisiMasterController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Master Lembar Disposisi',
            'menuTitle' => 'Disposisi',
            'menuSubtitle' => 'Master Lembar',
        ];

        return view('surat.disposisi-master.disposisi-master', $data);
    }

    // View / list semua master lembar
    public function views(Request $request)
    {
        $query = DisposisiMaster::query()
            ->leftJoin('jabatan', 'jabatan.id', '=', 'tbl_disposisi_master.id_jabatan_pemilik')
            ->select(
                'tbl_disposisi_master.id',
                'tbl_disposisi_master.nama_master',
                'tbl_disposisi_master.id_jabatan_pemilik',
                'tbl_disposisi_master.status',
                'jabatan.nama_jabatan as nama_pemilik'
            )
            ->orderBy('tbl_disposisi_master.nama_master');

        if ($request->filled('search')) {
            $query->where('tbl_disposisi_master.nama_master', 'like', '%' . $request->search . '%');
        }

        $data = [];
        foreach ($query->get() as $value) {
            $jumlahDetail = \App\Models\Surat\DisposisiMasterDetail::where('id_disposisi_master', $value->id)->count();

            $data[] = [
                'id' => $value->id,
                'nama_master' => $value->nama_master,
                'id_jabatan_pemilik' => $value->id_jabatan_pemilik,
                'nama_pemilik' => $value->nama_pemilik,
                'status' => $value->status,
                'jumlah_tujuan' => $jumlahDetail,
            ];
        }

        return response()->json($data, 200);
    }

    // Simpan
    public function store(Request $request)
    {
        $request->validate([
            'nama_master' => 'required|string|max:255',
            'id_jabatan_pemilik' => 'nullable|integer|exists:jabatan,id',
        ]);

        $query = DisposisiMaster::create([
            'nama_master' => $request->nama_master,
            'id_jabatan_pemilik' => $request->id_jabatan_pemilik,
            'status' => 'Aktif',
        ]);

        if ($query) {
            return response()->json([
                'success' => true,
                'data' => $query,
                'message' => 'Lembar Disposisi Berhasil Ditambahkan.',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Lembar Disposisi Gagal Ditambahkan.',
            ], 400);
        }
    }

    // Update
    public function update(Request $request, $id)
    {
        $master = DisposisiMaster::find($id);

        if (!$master) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        $master->update([
            'nama_master' => $request->nama_master,
            'id_jabatan_pemilik' => $request->id_jabatan_pemilik,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lembar Disposisi Berhasil Diupdate.',
        ], 200);
    }

    // Delete
    public function destroy($id)
    {
        $master = DisposisiMaster::find($id);

        if (!$master) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        $master->delete(); // detail ikut terhapus (onDelete cascade)

        return response()->json([
            'success' => true,
            'message' => 'Lembar Disposisi Berhasil Dihapus.',
        ], 200);
    }

    // Update status Aktif / Tidak Aktif
    public function updateStatus(Request $request, $id)
    {
        $query = DisposisiMaster::where('id', $id)->update([
            'status' => $request->status,
        ]);

        if ($query) {
            return response()->json([
                'success' => true,
                'message' => 'Sukses mengubah status menjadi ' . $request->status,
                'data' => [],
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status.',
                'data' => [],
            ], 400);
        }
    }
}
