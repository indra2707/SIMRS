<?php
namespace App\Http\Controllers\MasterData;
use App\Http\Controllers\Controller;
use App\Models\MaterData\Sertifikat;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class SertifikatController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title' => 'Kontrak',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Kontrak',
        ];
        return view('master-data.dokumen.dokumen', $data);
    }

    // Views Sertifikat
    public function views()
    {
        $idPegawai = Session::get('id_pegawai');
        if (!$idPegawai) {
            return response()->json([
                'message' => 'Session id_pegawai tidak ditemukan'
            ], 401);
        }

        $query = DB::table('tbl_sertifikat')
            ->where('id_pegawai', $idPegawai)
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id_sertifikat' => $value->id,
                'nama_sertifikat' => $value->nama,
                'jenis_sertifikat' => $value->jenis,
                'penyelenggara_sertifikat' => $value->penyelenggara,
                'tahun_sertifikat' => $value->tahun,
                'lampiran_sertifikat' => $value->lampiran,
            ];
        }
        return response()->json($data, 200);
    }

    // Simpan Sertifikat
    public function store(Request $request)
    {
        $fileName = null;
        if ($request->hasFile('lampiran-sertifikat')) {
            $file = $request->file('lampiran-sertifikat');

            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/sertifikat'), $fileName);
        }

        $query = Sertifikat::create([
            'id_pegawai' => Session::get('id_pegawai'),
            'nama' => $request->nama_sertifikat,
            'penyelenggara' => $request->penyelenggara_sertifikat,
            'tahun' => $request->tahun_sertifikat,
            'jenis' => $request->jenis_sertifikat,
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


    // Edit Sertifikat
    public function update(Request $request, $id)
    {
        $sertifikat = Sertifikat::find($id);

        if (!$sertifikat) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $fileName = $sertifikat->lampiran; // default file lama

        if ($request->hasFile('lampiran-sertifikat')) {

            // hapus file lama
            if (!empty($sertifikat->lampiran)) {
                $oldFile = public_path('uploads/sertifikat/' . $sertifikat->lampiran);
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            // upload file baru
            $file = $request->file('lampiran-sertifikat');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/sertifikat'), $fileName);
        }

        $sertifikat->update([
            'id_pegawai' => Session::get('id_pegawai'),
            'nama' => $request->nama_sertifikat,
            'penyelenggara' => $request->penyelenggara_sertifikat,
            'tahun' => $request->tahun_sertifikat,
            'jenis' => $request->jenis_sertifikat,
            'lampiran' => $fileName,
        ]);

        return response()->json([
            'success' => true,
            'data' => [],
            'message' => 'Data Berhasil Diupdate.',
        ], 200);
    }


    // delete Sertifikat
    public function destroy($id)
    {
        $sertifikat = Sertifikat::find($id);
        if (!$sertifikat) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }

        // hapus file jika ada
        if ($sertifikat->lampiran) {
            $filePath = public_path('uploads/sertifikat/' . $sertifikat->lampiran);

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $sertifikat->delete();
        return response()->json([
            'success' => true,
            'data' => [],
            'message' => 'Data Berhasil Dihapus.',
        ], 200);
    }

}
