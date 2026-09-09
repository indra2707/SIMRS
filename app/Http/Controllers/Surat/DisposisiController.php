<?php

namespace App\Http\Controllers\Surat;

use App\Http\Controllers\Controller;
// use App\Models\User;
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
     * Ambil daftar jabatan (dari template tbl_disposisi_jabatan) untuk
     * 1 id_aproval tertentu -- dipakai untuk menyusun form "Buat
     * Disposisi" (menampilkan daftar 8 jabatan seperti form kertas).
     */
    public function jabatanByAproval(Request $request)
    {
        $request->validate([
            'id_aproval' => 'required|integer',
        ]);
 
        $query = DB::table('tbl_disposisi_jabatan as dj')
            ->leftJoin('pegawai as p', 'p.id', '=', 'dj.id_pegawai')
            ->select(
                'dj.id',
                'dj.urutan',
                'dj.nama_jabatan',
                'dj.id_pegawai',
                'p.nama_pekerja'
            )
            ->where('dj.id_aproval', $request->id_aproval)
            ->orderBy('dj.urutan', 'asc')
            ->get();
 
        return response()->json($query, 200);
    }
 
    /**
     * Buat disposisi baru (header + banyak baris detail sekaligus).
     *
     * Body:
     *   id_surat, id_aproval, no_agenda, tingkat_surat (R/P/S/B), catatan
     *   penerima: array, tiap item:
     *     { id_disposisi_jabatan, nama_jabatan, id_pegawai,
     *       tindakan_action, tindakan_tanggapan, tindakan_info, tindakan_file }
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_surat' => 'required|integer|exists:surat,id',
            'id_aproval' => 'required|integer',
            'no_agenda' => 'nullable|string|max:100',
            'tingkat_surat' => 'nullable|in:R,P,S,B',
            'catatan' => 'nullable|string|max:1000',
 
            'penerima' => 'required|array|min:1',
            'penerima.*.nama_jabatan' => 'required|string',
            'penerima.*.id_pegawai' => 'nullable|integer',
        ], [
            'penerima.required' => 'Pilih minimal 1 jabatan penerima.',
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
            $idHeader = DB::table('tbl_disposisi_surat')->insertGetId([
                'id_surat' => $request->id_surat,
                'id_aproval' => $request->id_aproval,
                'no_agenda' => $request->no_agenda,
                'tingkat_surat' => $request->tingkat_surat,
                'catatan' => $request->catatan,
                'id_pengirim' => $idPengirim,
                'id_unit' => $idUnit,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
 
            foreach ($request->penerima as $p) {
                DB::table('tbl_disposisi_surat_detail')->insert([
                    'id_disposisi_surat' => $idHeader,
                    'id_disposisi_jabatan' => $p['id_disposisi_jabatan'] ?? null,
                    'nama_jabatan' => $p['nama_jabatan'],
                    'id_pegawai' => $p['id_pegawai'] ?? null,
                    'id_unit' => $idUnit,
 
                    'tindakan_action' => !empty($p['tindakan_action']),
                    'tindakan_tanggapan' => !empty($p['tindakan_tanggapan']),
                    'tindakan_info' => !empty($p['tindakan_info']),
                    'tindakan_file' => !empty($p['tindakan_file']),
 
                    'status' => 'Menunggu',
                    'tanggal_diteruskan' => now(),
 
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
 
            DB::commit();
 
            return response()->json([
                'success' => true,
                'message' => 'Disposisi berhasil dibuat ke ' . count($request->penerima) . ' penerima.',
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
     * Kotak masuk disposisi -- untuk pegawai yang sedang login.
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
 
        $query = DB::table('tbl_disposisi_surat_detail as dsd')
            ->join('tbl_disposisi_surat as ds', 'ds.id', '=', 'dsd.id_disposisi_surat')
            ->join('surat as s', 's.id', '=', 'ds.id_surat')
            ->leftJoin('pegawai as pengirim', 'pengirim.id', '=', 'ds.id_pengirim')
            ->select(
                'dsd.id as id_detail',
                'dsd.nama_jabatan',
                'dsd.tindakan_action',
                'dsd.tindakan_tanggapan',
                'dsd.tindakan_info',
                'dsd.tindakan_file',
                'dsd.status',
                'dsd.tanggal_diteruskan',
                'dsd.tanggal_dibaca',
                'dsd.tanggal_paraf',
                'dsd.catatan_tindak_lanjut',
 
                'ds.no_agenda',
                'ds.tingkat_surat',
                'ds.catatan as catatan_disposisi',
 
                's.id as id_surat',
                's.no_surat',
                's.tanggal',
                's.perihal',
                's.isi_surat',
                's.lampiran',
 
                'pengirim.nama_pekerja as nama_pengirim'
            )
            ->where('dsd.id_pegawai', $idPegawai)
            ->where('dsd.id_unit', $idUnit)
            ->orderByDesc('ds.created_at')
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
                'id_detail' => $value->id_detail,
                'id_surat' => $value->id_surat,
 
                'no_surat' => $value->no_surat,
                'tanggal' => $value->tanggal,
                'perihal' => $value->perihal,
                'isi_surat' => $value->isi_surat,
                'lampiran' => $lampiranArr,
 
                'no_agenda' => $value->no_agenda,
                'tingkat_surat' => $value->tingkat_surat,
                'catatan_disposisi' => $value->catatan_disposisi,
                'nama_jabatan' => $value->nama_jabatan,
                'nama_pengirim' => $value->nama_pengirim,
 
                'tindakan_action' => (bool) $value->tindakan_action,
                'tindakan_tanggapan' => (bool) $value->tindakan_tanggapan,
                'tindakan_info' => (bool) $value->tindakan_info,
                'tindakan_file' => (bool) $value->tindakan_file,
 
                'status' => $value->status,
                'tanggal_diteruskan' => $value->tanggal_diteruskan,
                'tanggal_dibaca' => $value->tanggal_dibaca,
                'tanggal_paraf' => $value->tanggal_paraf,
                'catatan_tindak_lanjut' => $value->catatan_tindak_lanjut,
            ];
        }
 
        return response()->json($data, 200);
    }
 
    /**
     * Tandai 'Dibaca' (otomatis saat penerima buka detail).
     */
    public function tandaiDibaca($id)
    {
        $idPegawai = session('id_pegawai');
 
        $row = DB::table('tbl_disposisi_surat_detail')
            ->where('id', $id)
            ->where('id_pegawai', $idPegawai)
            ->first();
 
        if (!$row) {
            return response()->json([
                'success' => false,
                'message' => 'Data disposisi tidak ditemukan.',
            ], 404);
        }
 
        if ($row->status === 'Menunggu') {
            DB::table('tbl_disposisi_surat_detail')
                ->where('id', $id)
                ->update([
                    'status' => 'Dibaca',
                    'tanggal_dibaca' => now(),
                ]);
        }
 
        return response()->json(['success' => true], 200);
    }
 
    /**
     * Paraf / tandai selesai ditindaklanjuti.
     */
    public function paraf(Request $request, $id)
    {
        $request->validate([
            'catatan_tindak_lanjut' => 'nullable|string|max:1000',
        ]);
 
        $idPegawai = session('id_pegawai');
 
        $row = DB::table('tbl_disposisi_surat_detail')
            ->where('id', $id)
            ->where('id_pegawai', $idPegawai)
            ->first();
 
        if (!$row) {
            return response()->json([
                'success' => false,
                'message' => 'Data disposisi tidak ditemukan.',
            ], 404);
        }
 
        DB::table('tbl_disposisi_surat_detail')
            ->where('id', $id)
            ->update([
                'status' => 'Selesai',
                'tanggal_paraf' => now(),
                'catatan_tindak_lanjut' => $request->catatan_tindak_lanjut,
            ]);
 
        return response()->json([
            'success' => true,
            'message' => 'Disposisi berhasil diparaf / ditandai selesai.',
        ], 200);
    }
 
    /**
     * Batalkan seluruh disposisi (header + semua detail) -- hanya
     * boleh oleh pengirim, dan hanya kalau BELUM ADA satupun penerima
     * yang memparaf/menindaklanjuti.
     */
    public function destroy($id)
    {
        $idPengirim = session('id_pegawai');
 
        $header = DB::table('tbl_disposisi_surat')
            ->where('id', $id)
            ->where('id_pengirim', $idPengirim)
            ->first();
 
        if (!$header) {
            return response()->json([
                'success' => false,
                'message' => 'Data disposisi tidak ditemukan.',
            ], 404);
        }
 
        $sudahAdaYangSelesai = DB::table('tbl_disposisi_surat_detail')
            ->where('id_disposisi_surat', $id)
            ->where('status', 'Selesai')
            ->exists();
 
        if ($sudahAdaYangSelesai) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa dibatalkan, sudah ada penerima yang menindaklanjuti.',
            ], 400);
        }
 
        DB::table('tbl_disposisi_surat')->where('id', $id)->delete();
        // detail otomatis ikut terhapus (onDelete cascade)
 
        return response()->json([
            'success' => true,
            'message' => 'Disposisi berhasil dibatalkan.',
        ], 200);
    }

}
