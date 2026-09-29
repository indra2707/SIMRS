<?php
namespace App\Http\Controllers\MasterData;
use App\Http\Controllers\Controller;
use App\Models\MaterData\DokumenLainnya;
use App\Models\Sdm\Pegawai;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class DokumenLainnyaController extends Controller
{
    private const MAP_JENIS_KE_PEGAWAI = [
        'KTP'                    => 'nik',
        'NPWP'                   => 'nomor_npwp',
        'BPJS Kesehatan'         => 'nomor_bpjskesehatan',
        'BPJS Ketenagakerjaan'   => 'nomor_bpjstk',
        // 'KK' => null,
    ];

    private function syncNomorKePegawai($idPegawai, $jenis, $nomor)
    {
        if (!array_key_exists($jenis, self::MAP_JENIS_KE_PEGAWAI)) {
            return;
        }

        $kolom = self::MAP_JENIS_KE_PEGAWAI[$jenis];

        Pegawai::where('id', $idPegawai)->update([
            $kolom => $nomor,
        ]);
    }


    public function jenisTerpakai($idPegawai)
    {
        $jenisTerpakai = DokumenLainnya::where('id_pegawai', $idPegawai)
            ->pluck('jenis');

        return response()->json([
            'success' => true,
            'data' => $jenisTerpakai,
        ], 200);
    }

    public function index()
    {
        $data = [
            'title' => 'Dokumen Lainnya',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Dokumen Lainnya',
        ];
        return view('sdm.dokumenlainnya.dokumenlainnya', $data);
    }

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

    public function store(Request $request)
    {
        $fileName = null;
        if ($request->hasFile('lampiran-dokumen-lainnya')) {
            $file = $request->file('lampiran-dokumen-lainnya');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/dokumen_lainnya'), $fileName);
        }

        $idPegawai = $request->id_pegawai ?: Session::get('id_pegawai');
        $jenis     = $request->jenis_dokumen_lainnya;
        $nomor     = $request->nomor_dokumen_lainnya;

        $sudahAda = DokumenLainnya::where('id_pegawai', $idPegawai)
            ->where('jenis', $jenis)
            ->exists();

        if ($sudahAda) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Pegawai ini sudah memiliki dokumen jenis ' . $jenis . '. Silakan edit data yang sudah ada.',
            ], 400);
        }

        try {
            $query = DB::transaction(function () use ($idPegawai, $jenis, $nomor, $fileName) {
                $dokumen = DokumenLainnya::create([
                    'id_pegawai' => $idPegawai,
                    'nomor'      => $nomor,
                    'jenis'      => $jenis,
                    'catatan'    => request('catatan_dokumen_lainnya'),
                    'lampiran'   => $fileName,
                ]);

                $this->syncNomorKePegawai($idPegawai, $jenis, $nomor);

                return $dokumen;
            });
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Data Gagal Ditambahkan: ' . $e->getMessage(),
            ], 400);
        }

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
        $jenis     = $request->jenis_dokumen_lainnya;
        $nomor     = $request->nomor_dokumen_lainnya;

        $sudahAda = DokumenLainnya::where('id_pegawai', $idPegawai)
            ->where('jenis', $jenis)
            ->where('id', '!=', $dokumen->id)
            ->exists();

        if ($sudahAda) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Pegawai ini sudah memiliki dokumen jenis ' . $jenis . '. Silakan edit data yang sudah ada.',
            ], 400);
        }

        try {
            DB::transaction(function () use ($dokumen, $idPegawai, $jenis, $nomor, $fileName) {
                $dokumen->update([
                    'id_pegawai' => $idPegawai,
                    'nomor'      => $nomor,
                    'jenis'      => $jenis,
                    'catatan'    => request('catatan_dokumen_lainnya'),
                    'lampiran'   => $fileName,
                ]);

                $this->syncNomorKePegawai($idPegawai, $jenis, $nomor);
            });
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Data Gagal Diupdate: ' . $e->getMessage(),
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => [],
            'message' => 'Data Berhasil Diupdate.',
        ], 200);
    }

    public function destroy($id)
    {
        $dokumen = DokumenLainnya::find($id);
        if (!$dokumen) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }

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
