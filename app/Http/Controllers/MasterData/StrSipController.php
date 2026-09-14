<?php
namespace App\Http\Controllers\MasterData;
use App\Http\Controllers\Controller;
use App\Models\MaterData\StrSip;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class StrSipController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title' => 'STR dan SIP',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'STR dan SIP',
        ];
        return view('sdm.strsip.strsip', $data);
    }

    // Views STR dan SIP
    public function views()
    {
        $idPegawai = Session::get('id_pegawai');
        if (!$idPegawai) {
            return response()->json([
                'message' => 'Session id_pegawai tidak ditemukan'
            ], 401);
        }

        $query = DB::table('tbl_str_sip')
            ->where('id_pegawai', $idPegawai)
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id_str' => $value->id,
                'nomor_str' => $value->nomor,
                'jenis_str' => $value->jenis,
                'tanggal_mulai_str' => Carbon::parse($value->tanggal_mulai)->format('d/m/Y'),
                'masa_berlaku_str' => $value->masa_berlaku,
                'tanggal_berakhir_str' => $value->tanggal_berakhir ? Carbon::parse($value->tanggal_berakhir)->format('d/m/Y') : '-',
                'lampiran_str' => $value->lampiran,
            ];
        }
        return response()->json($data, 200);
    }


    // Views STR dan SIP SDM
    public function viewssdm()
    {

        $query = DB::table('tbl_str_sip')
            ->join('pegawai', 'tbl_str_sip.id_pegawai', '=', 'pegawai.id')
            ->select(
                'tbl_str_sip.*',
                'pegawai.nama_pekerja',
            )
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id_str' => $value->id,
                'id_pegawai' => $value->id_pegawai,
                'nomor_str' => $value->nomor,
                'jenis_str' => $value->jenis,
                'tanggal_mulai_str' => Carbon::parse($value->tanggal_mulai)->format('d/m/Y'),
                'masa_berlaku_str' => $value->masa_berlaku,
                'tanggal_berakhir_str' => $value->tanggal_berakhir ? Carbon::parse($value->tanggal_berakhir)->format('d/m/Y') : '-',
                'lampiran_str' => $value->lampiran,
                'nama_pekerja' => $value->nama_pekerja,
            ];
        }
        return response()->json($data, 200);
    }

    // Simpan STR dan SIP
    public function store(Request $request)
    {
        $fileName = null;
        if ($request->hasFile('lampiran-str')) {
            $file = $request->file('lampiran-str');

            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/str'), $fileName);
        }

        $idPegawai = $request->id_pegawai ?: Session::get('id_pegawai');
        $query = StrSip::create([
            'id_pegawai' => $idPegawai,
            'nomor' => $request->nomor_str,
            'jenis' => $request->jenis_str,
            'masa_berlaku' => $request->has('masa_berlaku_str') ? '1' : '0',
            'tanggal_mulai' => Carbon::createFromFormat('d/m/Y', $request->tanggal_mulai_str)->format('Y-m-d'),
            'tanggal_berakhir' => !empty($request->tanggal_berakhir_str) ? Carbon::createFromFormat('d/m/Y', $request->tanggal_berakhir_str)->format('Y-m-d') : null,
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


    // Edit STR dan SIP
    public function update(Request $request, $id)
    {
        $kontrak = StrSip::find($id);

        if (!$kontrak) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $fileName = $kontrak->lampiran; // default file lama

        if ($request->hasFile('lampiran-str')) {

            // hapus file lama
            if (!empty($kontrak->lampiran)) {
                $oldFile = public_path('uploads/str/' . $kontrak->lampiran);
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            // upload file baru
            $file = $request->file('lampiran-str');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/str'), $fileName);
        }

        $idPegawai = $request->id_pegawai ?: Session::get('id_pegawai');
        $kontrak->update([
            'id_pegawai' => $idPegawai,
            'nomor' => $request->nomor_str,
            'jenis' => $request->jenis_str,
            'masa_berlaku' => $request->has('masa_berlaku_str') ? '1' : '0',
            'tanggal_mulai' => Carbon::createFromFormat('d/m/Y', $request->tanggal_mulai_str)->format('Y-m-d'),
            'tanggal_berakhir' => !empty($request->tanggal_berakhir_str) ? Carbon::parse($request->tanggal_berakhir_str)->format('Y-m-d') : null,
            'lampiran' => $fileName,
        ]);

        return response()->json([
            'success' => true,
            'data' => [],
            'message' => 'Data Berhasil Diupdate.',
        ], 200);
    }


    // delete STR dan SIP
    public function destroy($id)
    {
        $str = StrSip::find($id);
        if (!$str) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }

        // hapus file jika ada
        if ($str->lampiran) {
            $filePath = public_path('uploads/str/' . $str->lampiran);

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $str->delete();
        return response()->json([
            'success' => true,
            'data' => [],
            'message' => 'Data Berhasil Dihapus.',
        ], 200);
    }

}
