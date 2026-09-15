<?php
namespace App\Http\Controllers\MasterData;
use App\Http\Controllers\Controller;
use App\Models\MaterData\SkJabatan;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class SkJabatanController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title' => 'SK Jabatan',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'SK Jabatan',
        ];
        return view('sdm.jabatan.jabatan', $data);
    }

    // Views SK Jabatan
    public function views()
    {
        $idPegawai = Session::get('id_pegawai');
        if (!$idPegawai) {
            return response()->json([
                'message' => 'Session id_pegawai tidak ditemukan'
            ], 401);
        }

        $query = DB::table('tbl_sk_jabatan')
            ->where('id_pegawai', $idPegawai)
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id_jabatan' => $value->id,
                'nomor_sk' => $value->nomor_sk,
                'nama_jabatan' => $value->nama_jabatan,
                'tanggal_mulai_jabatan' => Carbon::parse($value->tanggal_mulai)->format('d/m/Y'),
                'tanggal_berakhir_jabatan' => Carbon::parse($value->tanggal_berakhir)->format('d/m/Y'),
                'lampiran_jabatan' => $value->lampiran,
            ];
        }
        return response()->json($data, 200);
    }


    // Views SK Jabatan
    public function viewssdm()
    {

        $query = DB::table('tbl_sk_jabatan')
            ->join('pegawai', 'tbl_sk_jabatan.id_pegawai', '=', 'pegawai.id')
            ->select(
                'tbl_sk_jabatan.*',
                'pegawai.nama_pekerja',
            )
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id_jabatan' => $value->id,
                'nama_pegawai' => $value->nama_pekerja,
                'nomor_sk' => $value->nomor_sk,
                'nama_jabatan' => $value->nama_jabatan,
                'tanggal_mulai_jabatan' => Carbon::parse($value->tanggal_mulai)->format('d/m/Y'),
                'tanggal_berakhir_jabatan' => Carbon::parse($value->tanggal_berakhir)->format('d/m/Y'),
                'lampiran_jabatan' => $value->lampiran,
                'id_pegawai' => $value->id_pegawai,
            ];
        }
        return response()->json($data, 200);
    }

    // Simpan SK jabatan
    public function store(Request $request)
    {
        $fileName = null;
        if ($request->hasFile('lampiran-jabatan')) {
            $file = $request->file('lampiran-jabatan');

            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/jabatan'), $fileName);
        }

        $idPegawai = $request->id_pegawai ?: Session::get('id_pegawai');

        $query = SkJabatan::create([
            'id_pegawai' => $idPegawai,
            'nomor_sk' => $request->nomor_sk,
            'nama_jabatan' => $request->nama_jabatan,
            'tanggal_mulai' => Carbon::createFromFormat('d/m/Y', $request->tanggal_mulai_jabatan)->format('Y-m-d'),
            'tanggal_berakhir' => Carbon::createFromFormat('d/m/Y', $request->tanggal_berakhir_jabatan)->format('Y-m-d'),
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


    // Edit SK Jabatan
    public function update(Request $request, $id)
    {
        $jabatan = SkJabatan::find($id);

        if (!$jabatan) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $fileName = $jabatan->lampiran; // default file lama

        if ($request->hasFile('lampiran-jabatan')) {

            // hapus file lama
            if (!empty($jabatan->lampiran)) {
                $oldFile = public_path('uploads/jabatan/' . $jabatan->lampiran);
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            // upload file baru
            $file = $request->file('lampiran-jabatan');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/jabatan'), $fileName);
        }

        $idPegawai = $request->id_pegawai ?: Session::get('id_pegawai');

        $jabatan->update([
            'id_pegawai' => $idPegawai,
            'nomor_sk' => $request->nomor_sk,
            'nama_jabatan' => $request->nama_jabatan,
            'tanggal_mulai' => Carbon::createFromFormat('d/m/Y', $request->tanggal_mulai_jabatan)->format('Y-m-d'),
            'tanggal_berakhir' => Carbon::createFromFormat('d/m/Y', $request->tanggal_berakhir_jabatan)->format('Y-m-d'),
            'lampiran' => $fileName,
        ]);

        return response()->json([
            'success' => true,
            'data' => [],
            'message' => 'Data Berhasil Diupdate.',
        ], 200);
    }


    // delete SK Jabatan
    public function destroy($id)
    {
        $jabatan = SkJabatan::find($id);
        if (!$jabatan) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }

        // hapus file jika ada
        if ($jabatan->lampiran) {
            $filePath = public_path('uploads/jabatan/' . $jabatan->lampiran);

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $jabatan->delete();
        return response()->json([
            'success' => true,
            'data' => [],
            'message' => 'Data Berhasil Dihapus.',
        ], 200);
    }

}