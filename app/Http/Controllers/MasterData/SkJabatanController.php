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
        return view('master-data.dokumen.dokumen', $data);
    }

    // Views Kontrak
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

    // Simpan Kontrak
    public function store(Request $request)
    {
        $fileName = null;
        if ($request->hasFile('lampiran-jabatan')) {
            $file = $request->file('lampiran-jabatan');

            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/jabatan'), $fileName);
        }

        $query = SkJabatan::create([
            'id_pegawai' => Session::get('id_pegawai'),
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


    // Edit Kontrak
    public function update(Request $request, $id)
    {
        $kontrak = SkJabatan::find($id);

        if (!$kontrak) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $fileName = $kontrak->lampiran; // default file lama

        if ($request->hasFile('lampiran-jabatan')) {

            // hapus file lama
            if (!empty($kontrak->lampiran)) {
                $oldFile = public_path('uploads/jabatan/' . $kontrak->lampiran);
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            // upload file baru
            $file = $request->file('lampiran-jabatan');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/jabatan'), $fileName);
        }

        $kontrak->update([
            'id_pegawai' => Session::get('id_pegawai'),
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


    // delete Kontrak
    public function destroy($id)
    {
        $kontrak = SkJabatan::find($id);
        if (!$kontrak) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }

        // hapus file jika ada
        if ($kontrak->lampiran) {
            $filePath = public_path('uploads/jabatan/' . $kontrak->lampiran);

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $kontrak->delete();
        return response()->json([
            'success' => true,
            'data' => [],
            'message' => 'Data Berhasil Dihapus.',
        ], 200);
    }

}