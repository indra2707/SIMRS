<?php

namespace App\Http\Controllers\Surat;

use App\Http\Controllers\Controller;
use App\Models\Surat\DisposisiMasterDetail;
use Illuminate\Http\Request;

class DisposisiMasterDetailController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Detail Lembar Disposisi',
            'menuTitle' => 'Disposisi',
            'menuSubtitle' => 'Detail Lembar',
        ];

        return view('surat.disposisi-master.disposisi-master-detail', $data);
    }

    // View, difilter berdasarkan id_disposisi_master (1 lembar)
    public function views(Request $request)
    {
        $query = DisposisiMasterDetail::query()
            ->leftJoin('jabatan', 'jabatan.id', '=', 'tbl_disposisi_master_detail.id_jabatan_tujuan')
            ->orderBy('tbl_disposisi_master_detail.urutan', 'asc')
            ->select(
                'tbl_disposisi_master_detail.id',
                'tbl_disposisi_master_detail.id_disposisi_master',
                'tbl_disposisi_master_detail.urutan',
                'tbl_disposisi_master_detail.id_jabatan_tujuan',
                'tbl_disposisi_master_detail.id_unit',
                'jabatan.nama_jabatan'
            );

        if ($request->filled('id_disposisi_master')) {
            $query->where('tbl_disposisi_master_detail.id_disposisi_master', $request->id_disposisi_master);
        }

        $data = [];
        foreach ($query->get() as $value) {
            $data[] = [
                'id_detail' => $value->id,
                'id_disposisi_master' => $value->id_disposisi_master,
                'urutan' => $value->urutan,
                'id_jabatan_tujuan' => $value->id_jabatan_tujuan,
                'nama_jabatan' => $value->nama_jabatan,
                'id_unit' => $value->id_unit,
            ];
        }

        return response()->json($data, 200);
    }

    // Simpan 1 baris nama tujuan
    public function store(Request $request)
    {
        $request->validate([
            'id_disposisi_master' => 'required|integer|exists:tbl_disposisi_master,id',
            'urutan' => 'required|integer|min:1',
            'id_jabatan_tujuan' => 'required|integer|exists:jabatan,id',
            'id_unit' => 'nullable|integer',
        ]);

        $query = DisposisiMasterDetail::create([
            'id_disposisi_master' => $request->id_disposisi_master,
            'urutan' => $request->urutan,
            'id_jabatan_tujuan' => $request->id_jabatan_tujuan,
            'id_unit' => $request->id_unit,
        ]);

        if ($query) {
            return response()->json([
                'success' => true,
                'data' => $query,
                'message' => 'Baris Tujuan Berhasil Ditambahkan.',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Baris Tujuan Gagal Ditambahkan.',
            ], 400);
        }
    }

    // Simpan banyak baris sekaligus (mis. saat setup awal 1 lembar penuh)
    public function storeBulk(Request $request)
    {
        $request->validate([
            'id_disposisi_master' => 'required|integer|exists:tbl_disposisi_master,id',
            'items' => 'required|array|min:1',
            'items.*.urutan' => 'required|integer|min:1',
            'items.*.id_jabatan_tujuan' => 'required|integer|exists:jabatan,id',
            'items.*.id_unit' => 'nullable|integer',
        ]);

        $dibuat = [];
        foreach ($request->items as $item) {
            $row = DisposisiMasterDetail::updateOrCreate(
                [
                    'id_disposisi_master' => $request->id_disposisi_master,
                    'id_jabatan_tujuan' => $item['id_jabatan_tujuan'],
                ],
                [
                    'urutan' => $item['urutan'],
                    'id_unit' => $item['id_unit'] ?? null,
                ]
            );
            $dibuat[] = $row->id;
        }

        return response()->json([
            'success' => true,
            'message' => count($dibuat) . ' baris tujuan tersimpan.',
            'data' => $dibuat,
        ], 200);
    }

    // Update
    public function update(Request $request, $id)
    {
        $detail = DisposisiMasterDetail::find($id);

        if (!$detail) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        $detail->update([
            'urutan' => $request->urutan,
            'id_jabatan_tujuan' => $request->id_jabatan_tujuan,
            'id_unit' => $request->id_unit,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Baris Tujuan Berhasil Diupdate.',
        ], 200);
    }

    // Delete
    public function destroy($id)
    {
        $detail = DisposisiMasterDetail::find($id);

        if (!$detail) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        $detail->delete();

        return response()->json([
            'success' => true,
            'message' => 'Baris Tujuan Berhasil Dihapus.',
        ], 200);
    }
}
