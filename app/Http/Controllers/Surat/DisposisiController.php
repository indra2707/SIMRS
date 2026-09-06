<?php

namespace App\Http\Controllers\Surat;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DisposisiController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Disposisi Surat',
            'menuTitle' => 'Surat',
            'menuSubtitle' => 'Disposisi',
        ];

        return view('surat.disposisi.disposisi', $data);
    }

    /**
     * List disposisi yang DITUJUKAN ke pegawai yang sedang login
     * (kotak masuk disposisi).
     */
    public function views(Request $request)
    {
        $idPegawai = session('id_pegawai');
        $idUnit = session('id_unit');

        if (!$idPegawai) {
            return response()->json([
                'success' => false,
                'message' => 'ID pegawai tidak ditemukan pada session.',
                'data' => [],
            ], 401);
        }

        $query = DB::table('disposisi as d')
            ->join('surat as s', 's.id', '=', 'd.id_surat')
            ->leftJoin('pegawai as pengirim', 'pengirim.id', '=', 'd.id_pengirim')
            ->leftJoin('pegawai as pembuat', 'pembuat.id', '=', 's.id_pegawai')
            ->select(
                'd.id as id_disposisi',
                'd.id_surat',
                'd.catatan',
                'd.status',
                'd.tanggal_dibaca',
                'd.tanggal_selesai',
                'd.catatan_tindak_lanjut',
                'd.created_at as tanggal_disposisi',

                's.no_surat',
                's.tanggal',
                's.perihal',
                's.isi_surat',
                's.lampiran',

                'pengirim.nama_pekerja as nama_pengirim',
                'pembuat.nama_pekerja as nama_pembuat_surat'
            )
            ->where('d.id_penerima', $idPegawai)
            ->where('d.id_unit', $idUnit)
            ->orderByDesc('d.created_at')
            ->get();

        $data = [];

        foreach ($query as $value) {
            $lampiranArr = [];
            if (!empty($value->lampiran)) {
                $lampiranArr = json_decode($value->lampiran, true);
                if (!is_array($lampiranArr)) {
                    $lampiranArr = [];
                }
            }

            $data[] = [
                'id_disposisi' => $value->id_disposisi,
                'id_surat' => $value->id_surat,

                'no_surat' => $value->no_surat,
                'tanggal' => $value->tanggal,
                'perihal' => $value->perihal,
                'isi_surat' => $value->isi_surat,
                'lampiran' => $lampiranArr,

                'nama_pengirim' => $value->nama_pengirim,
                'nama_pembuat_surat' => $value->nama_pembuat_surat,

                'catatan' => $value->catatan,
                'status' => $value->status,
                'catatan_tindak_lanjut' => $value->catatan_tindak_lanjut,

                'tanggal_disposisi' => $value->tanggal_disposisi,
                'tanggal_dibaca' => $value->tanggal_dibaca,
                'tanggal_selesai' => $value->tanggal_selesai,
            ];
        }

        return response()->json($data, 200);
    }

    /**
     * List disposisi yang PERNAH DIBUAT oleh pegawai yang sedang login
     * untuk 1 surat tertentu (dipakai buat cek "sudah didisposisikan ke
     * siapa saja" saat mau buat/tambah disposisi baru).
     */
    public function viewsBySurat(Request $request)
    {
        $query = DB::table('disposisi as d')
            ->leftJoin('pegawai as p', 'p.id', '=', 'd.id_penerima')
            ->select(
                'd.id',
                'd.id_penerima',
                'd.catatan',
                'd.status',
                'd.created_at as tanggal_disposisi',
                'p.nama_pekerja as nama_penerima'
            )
            ->where('d.id_surat', $request->id_surat)
            ->orderByDesc('d.created_at')
            ->get();

        return response()->json($query, 200);
    }

    /**
     * Buat disposisi baru -- bisa ke banyak penerima sekaligus.
     * Body: id_surat, penerima[] (array id_pegawai), catatan
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_surat' => 'required|integer|exists:surat,id',
            'penerima' => 'required|array|min:1',
            'penerima.*' => 'integer|distinct',
            'catatan' => 'nullable|string|max:1000',
        ], [
            'penerima.required' => 'Pilih minimal 1 penerima disposisi.',
        ]);

        $idUnit = session('id_unit');
        $idPengirim = session('id_pegawai');

        if (!$idPengirim || !$idUnit) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi pegawai/unit tidak ditemukan.',
            ], 401);
        }

        DB::beginTransaction();

        try {
            $dibuat = [];

            foreach ($request->penerima as $idPenerima) {

                // Hindari dobel disposisi ke orang yang sama untuk surat
                // yang sama kalau masih 'Menunggu'/'Dibaca' (belum Selesai)
                $sudahAda = DB::table('disposisi')
                    ->where('id_surat', $request->id_surat)
                    ->where('id_penerima', $idPenerima)
                    ->whereIn('status', ['Menunggu', 'Dibaca'])
                    ->exists();

                if ($sudahAda) {
                    continue;
                }

                $id = DB::table('disposisi')->insertGetId([
                    'id_surat' => $request->id_surat,
                    'id_unit' => $idUnit,
                    'id_pengirim' => $idPengirim,
                    'id_penerima' => $idPenerima,
                    'catatan' => $request->catatan,
                    'status' => 'Menunggu',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $dibuat[] = $id;
            }

            DB::commit();

            if (empty($dibuat)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Semua penerima yang dipilih sudah punya disposisi aktif untuk surat ini.',
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Disposisi berhasil dibuat ke ' . count($dibuat) . ' penerima.',
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat disposisi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Penerima membuka/melihat disposisi -> tandai 'Dibaca'.
     * Dipanggil otomatis saat penerima klik lihat detail.
     */
    public function tandaiDibaca($id)
    {
        $idPegawai = session('id_pegawai');

        $row = DB::table('disposisi')
            ->where('id', $id)
            ->where('id_penerima', $idPegawai)
            ->first();

        if (!$row) {
            return response()->json([
                'success' => false,
                'message' => 'Data disposisi tidak ditemukan.',
            ], 404);
        }

        if ($row->status === 'Menunggu') {
            DB::table('disposisi')
                ->where('id', $id)
                ->update([
                    'status' => 'Dibaca',
                    'tanggal_dibaca' => now(),
                ]);
        }

        return response()->json(['success' => true], 200);
    }

    /**
     * Penerima menandai disposisi sudah ditindaklanjuti/selesai.
     */
    public function selesaikan(Request $request, $id)
    {
        $request->validate([
            'catatan_tindak_lanjut' => 'nullable|string|max:1000',
        ]);

        $idPegawai = session('id_pegawai');

        $row = DB::table('disposisi')
            ->where('id', $id)
            ->where('id_penerima', $idPegawai)
            ->first();

        if (!$row) {
            return response()->json([
                'success' => false,
                'message' => 'Data disposisi tidak ditemukan.',
            ], 404);
        }

        DB::table('disposisi')
            ->where('id', $id)
            ->update([
                'status' => 'Selesai',
                'tanggal_selesai' => now(),
                'catatan_tindak_lanjut' => $request->catatan_tindak_lanjut,
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Disposisi ditandai selesai ditindaklanjuti.',
        ], 200);
    }

    /**
     * Batalkan disposisi -- hanya boleh oleh pengirimnya sendiri,
     * dan hanya kalau belum ditindaklanjuti (masih Menunggu/Dibaca).
     */
    public function destroy($id)
    {
        $idPengirim = session('id_pegawai');

        $row = DB::table('disposisi')
            ->where('id', $id)
            ->where('id_pengirim', $idPengirim)
            ->first();

        if (!$row) {
            return response()->json([
                'success' => false,
                'message' => 'Data disposisi tidak ditemukan.',
            ], 404);
        }

        if ($row->status === 'Selesai') {
            return response()->json([
                'success' => false,
                'message' => 'Disposisi yang sudah selesai ditindaklanjuti tidak bisa dibatalkan.',
            ], 400);
        }

        DB::table('disposisi')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Disposisi berhasil dibatalkan.',
        ], 200);
    }
}
