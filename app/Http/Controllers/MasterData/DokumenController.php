<?php
namespace App\Http\Controllers\MasterData;
use App\Http\Controllers\Controller;
use App\Models\MaterData\Ijazah;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class DokumenController extends Controller
{
    // Index Dokumen
    public function index()
    {
        $data = [
            'title' => 'Dokumen',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Dokumen',
        ];
        return view('master-data.dokumen.dokumen', $data);
    }

    // Index Ijazah
    public function index2()
    {
        $data = [
            'title' => 'Ijazah',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Ijazah',
        ];
        return view('sdm.ijazah.ijazah', $data);
    }

    // Views Ijazah
    public function views()
    {
        $idPegawai = Session::get('id_pegawai');
        if (!$idPegawai) {
            return response()->json([
                'message' => 'Session id_pegawai tidak ditemukan'
            ], 401);
        }

        $query = DB::table('tbl_ijazah')
            // ->join('pegawai', 'tbl_ijazah.id_pegawai', '=', 'pegawai.id')
            ->where('id_pegawai', $idPegawai)
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id' => $value->id,
                'institusi' => $value->institusi,
                'nomor_ijazah' => $value->nomor_ijazah,
                'pendidikan' => $value->pendidikan,
                'prodi' => $value->prodi,
                'tahun_lulus' => Carbon::parse($value->tahun_lulus)->format('m/Y'),
                'lampiran' => $value->lampiran,
                'id_ijazah' => $value->id,
                // 'nama_pekerja' => $value->nama_pekerja,
            ];
        }
        return response()->json($data, 200);
    }


    // Views Ijazah SDM
    public function viewssdm()
    {
        $query = DB::table('tbl_ijazah')
            ->join('pegawai', 'tbl_ijazah.id_pegawai', '=', 'pegawai.id')
            ->select(
                'tbl_ijazah.*',
                'pegawai.nama_pekerja',
            )
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id' => $value->id,
                'institusi' => $value->institusi,
                'nomor_ijazah' => $value->nomor_ijazah,
                'pendidikan' => $value->pendidikan,
                'prodi' => $value->prodi,
                'tahun_lulus' => Carbon::parse($value->tahun_lulus)->format('m/Y'),
                'lampiran' => $value->lampiran,
                'nama_pekerja' => $value->nama_pekerja,
                'id_pegawai' => $value->id_pegawai,
                'id_ijazah' => $value->id
            ];
        }
        return response()->json($data, 200);
    }

    // Simpan Ijazah
    public function store(Request $request)
    {
        $fileName = null;

        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');

            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/ijazah'), $fileName);
        }

        $idPegawai = $request->id_pegawai ?: Session::get('id_pegawai');

        $query = Ijazah::create([
            'id_pegawai' => $idPegawai,
            'nomor_ijazah' => $request->nomor_ijazah,
            'institusi' => $request->institusi,
            'pendidikan' => $request->pendidikan,
            'prodi' => $request->prodi,
            'tahun_lulus' => Carbon::createFromFormat('d/m/Y', $request->tahun_lulus)->format('Y-m-d'),
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


    // Edit Ijazah
    public function update(Request $request, $id)
    {
        $ijazah = Ijazah::find($id);

        if (!$ijazah) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $fileName = $ijazah->lampiran; // default file lama

        if ($request->hasFile('lampiran')) {

            // hapus file lama
            if (!empty($ijazah->lampiran)) {
                $oldFile = public_path('uploads/ijazah/' . $ijazah->lampiran);
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            // upload file baru
            $file = $request->file('lampiran');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/ijazah'), $fileName);
        }

        $idPegawai = $request->id_pegawai ?: Session::get('id_pegawai');

        $ijazah->update([
            'nomor_ijazah' => $request->nomor_ijazah,
            'id_pegawai' => $idPegawai,
            'institusi' => $request->institusi,
            'pendidikan' => $request->pendidikan,
            'prodi' => $request->prodi,
            'tahun_lulus' => $request->tahun_lulus
                ? Carbon::createFromFormat('m/Y', $request->tahun_lulus)
                    ->startOfMonth()
                    ->format('Y-m-d')
                : null,
            'lampiran' => $fileName,
        ]);

        return response()->json([
            'success' => true,
            'data' => [],
            'message' => 'Data Berhasil Diupdate.',
        ], 200);
    }


    // delete Ijazah
    public function destroy($id)
    {
        $ijazah = Ijazah::find($id);
        if (!$ijazah) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }

        // hapus file jika ada
        if ($ijazah->lampiran) {
            $filePath = public_path('uploads/ijazah/' . $ijazah->lampiran);

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $ijazah->delete();
        return response()->json([
            'success' => true,
            'data' => [],
            'message' => 'Data Berhasil Dihapus.',
        ], 200);
    }

}
