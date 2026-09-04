<?php

namespace App\Http\Controllers\Surat;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AprovalMemorandumController extends Controller
{
   

    public function index()
    {
        $data = [
            'title' => 'Approval Memorandum',
            'menuTitle' => 'Approval',
            'menuSubtitle' => 'Approval Memorandum',
        ];

        return view(
            'surat.aproval-memorandum.aproval',
            $data
        );
    }

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

        $query = DB::table('tbl_aproval_surat as aps')
            ->join(
                'surat as s',
                's.id',
                '=',
                'aps.id_surat'
            )
            ->leftJoin(
                'tbl_aproval as a',
                'a.id',
                '=',
                'aps.id_aproval'
            )
            ->leftJoin(
                'pegawai as p',
                'p.id',
                '=',
                's.id_pegawai'
            )
            ->select(
                'aps.id as id_aproval_surat',
                'aps.id_surat',
                'aps.id_aproval',
                'aps.parent_jabatan',
                'aps.id_pegawai',
                'aps.id_unit',
                'aps.tanggal_aproval',
                'aps.keterangan',
                'aps.status as status_aproval',

                's.tanggal',
                's.no_surat',
                's.perihal',
                's.isi_surat',
                's.lampiran',
                's.jumlah_lampiran',
                's.status as status_surat',
                's.created_at',

                'a.nama_aproval',

                'p.nama_pekerja as nama_pembuat'
            )
            ->where('aps.id_pegawai', $idPegawai)
            ->where('aps.id_unit', $idUnit)
            ->whereNull('aps.tanggal_aproval')

            ->where('s.status', 'Approve')
            ->orderByDesc('s.created_at')
            ->get();

        $data = [];

        foreach ($query as $value) {

            $adaApprovalSebelumnya = DB::table('tbl_aproval_surat')
                ->where('id_surat', $value->id_surat)
                ->where(
                    'id_aproval',
                    $value->id_aproval
                )
                ->where(
                    'parent_jabatan',
                    '>',
                    $value->parent_jabatan
                )
                ->whereNull('tanggal_aproval')
                ->exists();

            if ($adaApprovalSebelumnya) {
                continue;
            }

            $lampiranArr = [];
            if (!empty($value->lampiran)) {
                $lampiranArr = json_decode($value->lampiran, true);
                if (!is_array($lampiranArr)) {
                    $lampiranArr = [];
                }
            }

            $data[] = [
                'id_aproval_surat' => $value->id_aproval_surat,
                'id_surat' => $value->id_surat,
                'id_aproval' => $value->id_aproval,

                'tanggal' => $value->tanggal,
                'no_surat' => $value->no_surat,
                'perihal' => $value->perihal,
                'isi_surat' => $value->isi_surat,
                'lampiran' => $lampiranArr,

                'nama_aproval' => $value->nama_aproval,
                'nama_pembuat' => $value->nama_pembuat,

                'parent_jabatan' => $value->parent_jabatan,

                'status_surat' => $value->status_surat,
                'status_aproval' => $value->status_aproval,

                'tanggal_aproval' => $value->tanggal_aproval,
                'keterangan' => $value->keterangan,
            ];
        }

        return response()->json($data, 200);
    }

    /**
     * Approve 1 level approval (baris di tbl_aproval_surat).
     * $id = id baris tbl_aproval_surat (BUKAN id surat).
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $idPegawai = session('id_pegawai');
        $idUnit = session('id_unit');

        if (!$idPegawai) {
            return response()->json([
                'success' => false,
                'message' => 'ID pegawai tidak ditemukan pada session.',
            ], 401);
        }

        $row = DB::table('tbl_aproval_surat')
            ->where('id', $id)
            ->where('id_pegawai', $idPegawai)
            ->where('id_unit', $idUnit)
            ->whereNull('tanggal_aproval')
            ->first();

        if (!$row) {
            return response()->json([
                'success' => false,
                'message' => 'Data approval tidak ditemukan, atau sudah diproses sebelumnya.',
            ], 404);
        }

        DB::beginTransaction();

        try {
            DB::table('tbl_aproval_surat')
                ->where('id', $id)
                ->update([
                    'tanggal_aproval' => now(),
                    'keterangan' => $request->keterangan,
                    'status' => 'Approve',
                ]);

            // Cek apakah masih ada level lain (untuk surat + workflow yang sama)
            // yang belum diproses.
            $masihAdaPending = DB::table('tbl_aproval_surat')
                ->where('id_surat', $row->id_surat)
                ->where('id_aproval', $row->id_aproval)
                ->whereNull('tanggal_aproval')
                ->exists();

            if (!$masihAdaPending) {
                // Semua level sudah approve -> proses selesai
                DB::table('surat')
                    ->where('id', $row->id_surat)
                    ->update(['status' => 'Selesai']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $masihAdaPending
                    ? 'Surat berhasil di-approve, menunggu approval level berikutnya.'
                    : 'Surat berhasil di-approve dan proses approval selesai.',
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses approval.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Tolak 1 level approval -- keterangan/catatan revisi WAJIB diisi.
     * $id = id baris tbl_aproval_surat (BUKAN id surat).
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'required|string|max:1000',
        ], [
            'keterangan.required' => 'Keterangan/catatan revisi wajib diisi saat menolak.',
        ]);

        $idPegawai = session('id_pegawai');
        $idUnit = session('id_unit');

        if (!$idPegawai) {
            return response()->json([
                'success' => false,
                'message' => 'ID pegawai tidak ditemukan pada session.',
            ], 401);
        }

        $row = DB::table('tbl_aproval_surat')
            ->where('id', $id)
            ->where('id_pegawai', $idPegawai)
            ->where('id_unit', $idUnit)
            ->whereNull('tanggal_aproval')
            ->first();

        if (!$row) {
            return response()->json([
                'success' => false,
                'message' => 'Data approval tidak ditemukan, atau sudah diproses sebelumnya.',
            ], 404);
        }

        DB::beginTransaction();

        try {
            DB::table('tbl_aproval_surat')
                ->where('id', $id)
                ->update([
                    'tanggal_aproval' => now(),
                    'keterangan' => $request->keterangan,
                    'status' => 'Tolak',
                ]);

            // Tolak di level manapun -> proses berhenti, surat balik ke
            // status Revisi supaya pembuat surat bisa perbaiki.
            DB::table('surat')
                ->where('id', $row->id_surat)
                ->update(['status' => 'Revisi']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Surat ditolak dan dikembalikan untuk revisi.',
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses penolakan.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
