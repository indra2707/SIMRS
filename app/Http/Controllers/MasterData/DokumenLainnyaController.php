<?php
namespace App\Http\Controllers\MasterData;
use App\Http\Controllers\Controller;
use App\Models\MaterData\DokumenLainnya;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class DokumenLainnyaController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title' => 'Dokumen Lainnya',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Dokumen Lainnya',
        ];
        return view('sdm.dokumenlainnya.dokumenlainnya', $data);
    }

    // Views Dokumen Lainnya
    public function views()
    {
        $idPegawai = Session::get('id_pegawai');
        if (!$idPegawai) {
            return response()->json([
                'message' => 'Session id_pegawai tidak ditemukan'
            ], 401);
        }

        $query = DB::table('tbl_dokumen_lainnya')
            ->where('id_pegawai', $idPegawai)
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id_dokumen_lainnya' => $value->id,
                'jenis_dokumen_lainnya' => $value->jenis,
                'nomor_dokumen_lainnya' => $value->nomor,
                'catatan_dokumen_lainnya' => $value->catatan,
                'lampiran_dokumen_lainnya' => $value->lampiran,
            ];
        }
        return response()->json($data, 200);
    }


    // Views Dokumen Lainnya sdm
    public function viewssdm()
    {
        $query = DB::table('tbl_dokumen_lainnya')
            ->join('pegawai', 'tbl_dokumen_lainnya.id_pegawai', '=', 'pegawai.id')
            ->select(
                'tbl_dokumen_lainnya.*',
                'pegawai.nama_pekerja',
            )
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id_pegawai' => $value->id_pegawai,
                'nama_pekerja' => $value->nama_pekerja,
                'id_dokumen_lainnya' => $value->id,
                'jenis_dokumen_lainnya' => $value->jenis,
                'nomor_dokumen_lainnya' => $value->nomor,
                'catatan_dokumen_lainnya' => $value->catatan,
                'lampiran_dokumen_lainnya' => $value->lampiran,
            ];
        }
        return response()->json($data, 200);
    }

    // Simpan Dokumen Lainnya
    public function store(Request $request)
    {
        $fileName = null;
        if ($request->hasFile('lampiran-dokumen-lainnya')) {
            $file = $request->file('lampiran-dokumen-lainnya');

            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/dokumen_lainnya'), $fileName);
        }

        $idPegawai = $request->id_pegawai ?: Session::get('id_pegawai');

        $query = DokumenLainnya::create([
            'id_pegawai' => $idPegawai,
            'nomor' => $request->nomor_dokumen_lainnya,
            'jenis' => $request->jenis_dokumen_lainnya,
            'catatan' => $request->catatan_dokumen_lainnya,
            'lampiran' => $fileName,
        ]);

        if ($query) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Data Berhasil Ditambahkan.',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'data' => [],
            'message' => 'Data Gagal Ditambahkan.',
        ], 400);
    }


    // Edit Dokumen Lainnya
    public function update(Request $request, $id)
    {
        $dokumen = DokumenLainnya::find($id);

        if (!$dokumen) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $fileName = $dokumen->lampiran; // default file lama

        if ($request->hasFile('lampiran-dokumen-lainnya')) {

            // hapus file lama
            if (!empty($dokumen->lampiran)) {
                $oldFile = public_path('uploads/dokumen_lainnya/' . $dokumen->lampiran);
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            // upload file baru
            $file = $request->file('lampiran-dokumen-lainnya');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/dokumen_lainnya'), $fileName);
        }

        $idPegawai = $request->id_pegawai ?: Session::get('id_pegawai');
        
        $dokumen->update([
            'id_pegawai' => $idPegawai,
            'nomor' => $request->nomor_dokumen_lainnya,
            'jenis' => $request->jenis_dokumen_lainnya,
            'catatan' => $request->catatan_dokumen_lainnya,
            'lampiran' => $fileName,
        ]);

        return response()->json([
            'success' => true,
            'data' => [],
            'message' => 'Data Berhasil Diupdate.',
        ], 200);
    }


    // delete Dokumen Lainnya
    public function destroy($id)
    {
        $dokumen = DokumenLainnya::find($id);
        if (!$dokumen) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }

        // hapus file jika ada
        if ($dokumen->lampiran) {
            $filePath = public_path('uploads/dokumen_lainnya/' . $dokumen->lampiran);

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $dokumen->delete();
        return response()->json([
            'success' => true,
            'data' => [],
            'message' => 'Data Berhasil Dihapus.',
        ], 200);
    }

}
