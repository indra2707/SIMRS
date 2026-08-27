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

        // $users = User::where('status', 'aktif')
        //     ->orderBy('username', 'asc')
        //     ->get();

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

            $data[] = [
                'id_aproval_surat' => $value->id_aproval_surat,
                'id_surat' => $value->id_surat,
                'id_aproval' => $value->id_aproval,

                'tanggal' => $value->tanggal,
                'no_surat' => $value->no_surat,
                'perihal' => $value->perihal,

                'nama_aproval' => $value->nama_aproval,
                'nama_pembuat' => $value->nama_pembuat,

                'parent_jabatan' => $value->parent_jabatan,

                'status_surat' => $value->status_surat,

                'tanggal_aproval' => $value->tanggal_aproval,
                'keterangan' => $value->keterangan,
            ];
        }

        return response()->json($data, 200);
    }
}
