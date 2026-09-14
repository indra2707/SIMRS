<?php
namespace App\Http\Controllers\MasterData;
use App\Http\Controllers\Controller;
use App\Models\MaterData\HasilMcu;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class HasilMcuController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title' => 'MCU',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'MCU',
        ];
        return view('sdm.mcu.mcu', $data);
    }

    // Views Hasil MCU
    public function views()
    {
        $idPegawai = Session::get('id_pegawai');
        if (!$idPegawai) {
            return response()->json([
                'message' => 'Session id_pegawai tidak ditemukan'
            ], 401);
        }

        $query = DB::table('tbl_mcu')
            ->where('id_pegawai', $idPegawai)
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id_mcu' => $value->id,
                'tanggal_mcu' => Carbon::parse($value->tanggal)->format('d/m/Y'),
                'hasil_mcu' => $value->hasil,
                'catatan_mcu' => $value->catatan,
                'lampiran_mcu' => $value->lampiran,
            ];
        }
        return response()->json($data, 200);
    }


    // Views Hasil MCU
    public function viewssdm()
    {
        $query = DB::table('tbl_mcu')
            ->join('pegawai', 'tbl_mcu.id_pegawai', '=', 'pegawai.id')
            ->select(
                'tbl_mcu.*',
                'pegawai.nama_pekerja',
            )
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id_mcu' => $value->id,
                'id_pegawai' => $value->id_pegawai,
                'nama_pekerja' => $value->nama_pekerja,
                'tanggal_mcu' => Carbon::parse($value->tanggal)->format('d/m/Y'),
                'hasil_mcu' => $value->hasil,
                'catatan_mcu' => $value->catatan,
                'lampiran_mcu' => $value->lampiran,
            ];
        }
        return response()->json($data, 200);
    }

    // Simpan Hasil MCU
    public function store(Request $request)
    {
        $fileName = null;
        if ($request->hasFile('lampiran-mcu')) {
            $file = $request->file('lampiran-mcu');

            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/mcu'), $fileName);
        }

        $idPegawai = $request->id_pegawai ?: Session::get('id_pegawai');

        $query = HasilMcu::create([
            'id_pegawai' => $idPegawai,
            'tanggal' => Carbon::createFromFormat('d/m/Y', $request->tanggal_mcu)->format('Y-m-d'),
            'hasil' => $request->hasil_mcu,
            'catatan' => $request->catatan_mcu,
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


    // Edit Hasil MCU
    public function update(Request $request, $id)
    {
        $hasilMcu = HasilMcu::find($id);

        if (!$hasilMcu) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $fileName = $hasilMcu->lampiran; // default file lama

        if ($request->hasFile('lampiran-mcu')) {

            // hapus file lama
            if (!empty($hasilMcu->lampiran)) {
                $oldFile = public_path('uploads/mcu/' . $hasilMcu->lampiran);
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            // upload file baru
            $file = $request->file('lampiran-mcu');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/mcu'), $fileName);
        }

        $idPegawai = $request->id_pegawai ?: Session::get('id_pegawai');
        $hasilMcu->update([
            'id_pegawai' => $idPegawai,
            'tanggal' => Carbon::createFromFormat('d/m/Y', $request->tanggal_mcu)->format('Y-m-d'),
            'hasil' => $request->hasil_mcu,
            'catatan' => $request->catatan_mcu,
            'lampiran' => $fileName,
        ]);

        return response()->json([
            'success' => true,
            'data' => [],
            'message' => 'Data Berhasil Diupdate.',
        ], 200);
    }


    // delete Hasil MCU
    public function destroy($id)
    {
        $hasilMcu = HasilMcu::find($id);
        if (!$hasilMcu) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }

        // hapus file jika ada
        if ($hasilMcu->lampiran) {
            $filePath = public_path('uploads/mcu/' . $hasilMcu->lampiran);

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $hasilMcu->delete();
        return response()->json([
            'success' => true,
            'data' => [],
            'message' => 'Data Berhasil Dihapus.',
        ], 200);
    }

}
