<?php
namespace App\Http\Controllers\MasterData;
use App\Http\Controllers\Controller;
use App\Models\MaterData\Kontrak;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class KontrakController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title' => 'Kontrak',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Kontrak',
        ];
        return view('sdm.kontrak.kontrak', $data);
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

        $query = DB::table('tbl_kontrak')
            ->where('id_pegawai', $idPegawai)
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id_kontrak' => $value->id,
                'nomor_kontrak' => $value->nomor_kontrak,
                'status' => $value->status,
                'tanggal_mulai' => Carbon::parse($value->tanggal_mulai)->format('d/m/Y'),
                'masa_berlaku' => $value->masa_berlaku,
                'tanggal_berakhir' => $value->tanggal_berakhir ? Carbon::parse($value->tanggal_berakhir)->format('d/m/Y') : '-',
                'lampiran' => $value->lampiran,
            ];
        }
        return response()->json($data, 200);
    }


    // View Kontrak SDM
    public function viewssdm()
    {
        $query = DB::table('tbl_kontrak')
            ->join('pegawai', 'tbl_kontrak.id_pegawai', '=', 'pegawai.id')
            ->select(
                'tbl_kontrak.*',
                'pegawai.nama_pekerja',
            )
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id_kontrak' => $value->id,
                'nama_pekerja' => $value->nama_pekerja,
                'nomor_kontrak' => $value->nomor_kontrak,
                'status' => $value->status,
                'tanggal_mulai' => Carbon::parse($value->tanggal_mulai)->format('d/m/Y'),
                'masa_berlaku' => $value->masa_berlaku,
                'tanggal_berakhir' => $value->tanggal_berakhir ? Carbon::parse($value->tanggal_berakhir)->format('d/m/Y') : '-',
                'lampiran' => $value->lampiran,
                'id_pegawai' => $value->id_pegawai,
            ];
        }
        return response()->json($data, 200);
    }




    // Simpan Kontrak
    public function store(Request $request)
    {
        $fileName = null;
        if ($request->hasFile('lampiran-kontrak')) {
            $file = $request->file('lampiran-kontrak');

            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/kontrak'), $fileName);
        }

        $idPegawai = $request->id_pegawai ?: Session::get('id_pegawai');

        $query = Kontrak::create([
            'id_pegawai' => $idPegawai,
            'nomor_kontrak' => $request->nomor_kontrak,
            'status' => $request->status,
            'masa_berlaku' => $request->masa_berlaku ?? 0,
            'tanggal_mulai' => Carbon::createFromFormat('d/m/Y', $request->tanggal_mulai)->format('Y-m-d'),
            'tanggal_berakhir' => !empty($request->tanggal_berakhir) ? Carbon::createFromFormat('d/m/Y', $request->tanggal_berakhir)->format('Y-m-d') : null,
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
        $kontrak = Kontrak::find($id);

        if (!$kontrak) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $fileName = $kontrak->lampiran; // default file lama

        if ($request->hasFile('lampiran-kontrak')) {

            // hapus file lama
            if (!empty($kontrak->lampiran)) {
                $oldFile = public_path('uploads/kontrak/' . $kontrak->lampiran);
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            // upload file baru
            $file = $request->file('lampiran-kontrak');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/kontrak'), $fileName);
        }

        $idPegawai = $request->id_pegawai ?: Session::get('id_pegawai');

        $kontrak->update([
            'id_pegawai' => $idPegawai,
            'nomor_kontrak' => $request->nomor_kontrak,
            'status' => $request->status,
            'masa_berlaku' => $request->masa_berlaku ?? 0,
            'tanggal_mulai' => Carbon::createFromFormat('d/m/Y', $request->tanggal_mulai)->format('Y-m-d'),
            'tanggal_berakhir' => !empty($request->tanggal_berakhir) ? Carbon::createFromFormat('d/m/Y', $request->tanggal_berakhir)->format('Y-m-d') : null,
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
        $kontrak = Kontrak::find($id);
        if (!$kontrak) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }

        // hapus file jika ada
        if ($kontrak->lampiran) {
            $filePath = public_path('uploads/kontrak/' . $kontrak->lampiran);

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
