<?php

namespace App\Http\Controllers\Surat;

use App\Http\Controllers\Controller;
use App\Models\Surat\DisposisiJabatan;
use Illuminate\Http\Request;

class DisposisiJabatanController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Template Jabatan Disposisi',
            'menuTitle' => 'Aproval',
            'menuSubtitle' => 'Template Jabatan Disposisi',
        ];
        return view('surat.disposisi-jabatan.disposisi-jabatan', $data);
    }
 
    // View
    public function views(Request $request)
    {
        $query = DisposisiJabatan::query()
            ->leftJoin('pegawai', 'pegawai.id', '=', 'tbl_disposisi_jabatan.id_pegawai')
            ->orderBy('tbl_disposisi_jabatan.urutan', 'asc')
            ->select(
                'tbl_disposisi_jabatan.id',
                'tbl_disposisi_jabatan.id_aproval',
                'tbl_disposisi_jabatan.urutan',
                'tbl_disposisi_jabatan.nama_jabatan',
                'tbl_disposisi_jabatan.id_pegawai',
                'tbl_disposisi_jabatan.id_unit',
                'pegawai.nama_pekerja'
            );
 
        // Filter berdasarkan id_aproval (dipanggil dari modal Hirarki
        // Disposisi, sama seperti pola Aproval Detail)
        if ($request->filled('id_aproval')) {
            $query->where('tbl_disposisi_jabatan.id_aproval', $request->id_aproval);
        }
 
        $data = [];
 
        foreach ($query->get() as $value) {
            $data[] = [
                'id' => $value->id,
                'id_aproval' => $value->id_aproval,
                'urutan' => $value->urutan,
                'nama_jabatan' => $value->nama_jabatan,
                'id_pegawai' => $value->id_pegawai,
                'nama_pekerja' => $value->nama_pekerja,
                'id_unit' => $value->id_unit,
            ];
        }
 
        return response()->json($data, 200);
    }
 
    // Simpan
    public function store(Request $request)
    {
        $request->validate([
            'id_aproval' => 'required|integer',
            'urutan' => 'required|integer|min:1',
            'nama_jabatan' => 'required|string|max:255',
            'id_pegawai' => 'nullable|integer',
            'id_unit' => 'required|integer',
        ]);
 
        $query = DisposisiJabatan::create([
            'id_aproval' => $request->id_aproval,
            'urutan' => $request->urutan,
            'nama_jabatan' => $request->nama_jabatan,
            'id_pegawai' => $request->id_pegawai,
            'id_unit' => $request->id_unit,
        ]);
 
        if ($query) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Data Berhasil Ditambahkan.',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Data Gagal Ditambahkan.',
            ], 400);
        }
    }
 
    // Update
    public function update(Request $request, $id)
    {
        $jabatan = DisposisiJabatan::find($id);
 
        if (!$jabatan) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }
 
        $jabatan->update([
            'id_aproval' => $request->id_aproval,
            'urutan' => $request->urutan,
            'nama_jabatan' => $request->nama_jabatan,
            'id_pegawai' => $request->id_pegawai,
            'id_unit' => $request->id_unit,
        ]);
 
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Diupdate.',
        ], 200);
    }
 
    // Delete
    public function destroy($id)
    {
        $jabatan = DisposisiJabatan::find($id);
 
        if (!$jabatan) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
 
        $jabatan->delete();
 
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus.'
        ], 200);
    }

}
