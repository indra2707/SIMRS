<?php
namespace App\Http\Controllers\MasterData;
use App\Http\Controllers\Controller;
use App\Models\MaterData\SpkRkk;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class SpkRkkController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title' => 'SPK dan RKK',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'SPK dan RKK',
        ];
        return view('sdm.spkrkk.spkrkk', $data);
    }

    // Views SPK dan RKK
    public function views()
    {
        $idPegawai = Session::get('id_pegawai');
        if (!$idPegawai) {
            return response()->json([
                'message' => 'Session id_pegawai tidak ditemukan'
            ], 401);
        }

        $query = DB::table('tbl_spk_rkk')
            ->where('id_pegawai', $idPegawai)
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id_spk' => $value->id,
                'nomor_spk' => $value->nomor,
                'tanggal_mulai_spk' => Carbon::parse($value->tanggal_mulai)->format('d/m/Y'),
                'tanggal_berakhir_spk' => Carbon::parse($value->tanggal_berakhir)->format('d/m/Y'),
                'lampiran_spk' => $value->lampiran,
            ];
        }
        return response()->json($data, 200);
    }


    // Views SPK dan RKK SDM
    public function viewssdm()
    {
        $query = DB::table('tbl_spk_rkk')
            ->join('pegawai', 'tbl_spk_rkk.id_pegawai', '=', 'pegawai.id')
            ->select(
                'tbl_spk_rkk.*',
                'pegawai.nama_pekerja',
            )
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id_spk' => $value->id,
                'id_pegawai' => $value->id_pegawai,
                'nama_pekerja' => $value->nama_pekerja,
                'nomor_spk' => $value->nomor,
                'tanggal_mulai_spk' => Carbon::parse($value->tanggal_mulai)->format('d/m/Y'),
                'tanggal_berakhir_spk' => Carbon::parse($value->tanggal_berakhir)->format('d/m/Y'),
                'lampiran_spk' => $value->lampiran,
            ];
        }
        return response()->json($data, 200);
    }

    // Simpan SPK dan RKK
    public function store(Request $request)
    {
        $fileName = null;
        if ($request->hasFile('lampiran-spk')) {
            $file = $request->file('lampiran-spk');

            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/spk'), $fileName);
        }

        $idPegawai = $request->id_pegawai ?: Session::get('id_pegawai');

        $query = SpkRkk::create([
            'id_pegawai' => $idPegawai,
            'nomor' => $request->nomor_spk,
            'tanggal_mulai' => Carbon::createFromFormat('d/m/Y', $request->tanggal_mulai_spk)->format('Y-m-d'),
            'tanggal_berakhir' => Carbon::createFromFormat('d/m/Y', $request->tanggal_berakhir_spk)->format('Y-m-d'),
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


    // Edit SPK dan RKK
    public function update(Request $request, $id)
    {
        $kontrak = SpkRkk::find($id);

        if (!$kontrak) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $fileName = $kontrak->lampiran; // default file lama

        if ($request->hasFile('lampiran-spk')) {

            // hapus file lama
            if (!empty($kontrak->lampiran)) {
                $oldFile = public_path('uploads/spk/' . $kontrak->lampiran);
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            // upload file baru
            $file = $request->file('lampiran-spk');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/spk'), $fileName);
        }

        $idPegawai = $request->id_pegawai ?: Session::get('id_pegawai');
        
        $kontrak->update([
            'id_pegawai' => $idPegawai,
            'nomor' => $request->nomor_spk,
            'tanggal_mulai' => Carbon::createFromFormat('d/m/Y', $request->tanggal_mulai_spk)->format('Y-m-d'),
            'tanggal_berakhir' => Carbon::createFromFormat('d/m/Y', $request->tanggal_berakhir_spk)->format('Y-m-d'),
            'lampiran' => $fileName,
        ]);

        return response()->json([
            'success' => true,
            'data' => [],
            'message' => 'Data Berhasil Diupdate.',
        ], 200);
    }


    // delete SPK dan RKK
    public function destroy($id)
    {
        $spk = SpkRkk::find($id);
        if (!$spk) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }

        // hapus file jika ada
        if ($spk->lampiran) {
            $filePath = public_path('uploads/spk/' . $spk->lampiran);

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $spk->delete();
        return response()->json([
            'success' => true,
            'data' => [],
            'message' => 'Data Berhasil Dihapus.',
        ], 200);
    }

}
