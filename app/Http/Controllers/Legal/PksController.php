<?php

namespace App\Http\Controllers\Legal;

use App\Http\Controllers\Controller;
use App\Models\Legal\Pks;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PksController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title' => 'PKS',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'PKS',
        ];
        return view('legal.pks.pks', $data);
    }

    // Views Table
    public function views(Request $request)
    {
        $query = Pks::query()
            ->from('tbl_pks')
            ->join('tbl_jenis_kontrak', 'tbl_pks.id_jenis_kontrak', '=', 'tbl_jenis_kontrak.id')
            ->select(
                'tbl_pks.*',
                'tbl_jenis_kontrak.nama as nama_jenis_kontrak'
            );

        // FILTER RANGE TANGGAL
        if ($request->filled('tgl_awal') && $request->filled('tgl_akhir')) {
            $query->where(function ($q) use ($request) {
                $q->whereBetween('tanggal_mulai', [
                    $request->tgl_awal,
                    $request->tgl_akhir
                ])
                    ->orWhereNull('tanggal_mulai');
            });
        }

        // FILTER STATUS
        if ($request->filled('status')) {
            $query->where('tbl_pks.status', $request->status);
        }

        $query->groupBy('tbl_pks.judul');
        $query->orderBy('tbl_pks.tanggal_mulai', 'desc');


        $data = [];
        foreach ($query->get() as $value) {

            $sisa_hari = 0;

            if ($value->tanggal_selesai) {
                $today = Carbon::today();
                $selesai = Carbon::parse($value->tanggal_selesai);

                $sisa_hari = max(0, $today->diffInDays($selesai, false));
            }

            $data[] = [
                'id' => $value->id,
                'judul' => $value->judul,
                'nama_jenis_kontrak' => $value->nama_jenis_kontrak,
                'id_jenis_kontrak' => $value->id_jenis_kontrak,
                'pihak' => $value->pihak,
                'tanggal_mulai' => $value->tanggal_mulai ? Carbon::parse($value->tanggal_mulai)->format('d/m/Y') : null,
                'tanggal_selesai' => $value->tanggal_selesai ? Carbon::parse($value->tanggal_selesai)->format('d/m/Y') : null,
                'nomor_kontrak' => $value->nomor_kontrak,
                'status' => $value->status,
                'file' => $value->file,
                'sisa_hari' => $sisa_hari,
            ];
        }

        return response()->json($data, 200);
    }


    //notifikasi
    public function notify()
    {
        $today = Carbon::today();
        $batas = Carbon::today()->addDays(60);

        $data = Pks::query()
            ->from('tbl_pks')
            ->join('tbl_jenis_kontrak', 'tbl_pks.id_jenis_kontrak', '=', 'tbl_jenis_kontrak.id')
            ->select(
                'tbl_pks.*',
                'tbl_jenis_kontrak.nama as nama_jenis_kontrak'
            )
            ->where('tbl_pks.status', '1')
            ->whereNotNull('tbl_pks.tanggal_selesai')
            ->whereDate('tbl_pks.tanggal_selesai', '>=', $today)
            ->whereDate('tbl_pks.tanggal_selesai', '<=', $batas)
            ->orderBy('tbl_pks.tanggal_selesai', 'asc')
            ->get()
            ->map(function ($value) use ($today) {

                $selesai = Carbon::parse($value->tanggal_selesai);
                $sisa_hari = $today->diffInDays($selesai, false);

                return [
                    'id' => $value->id,
                    'judul' => $value->judul,
                    'nama_jenis_kontrak' => $value->nama_jenis_kontrak,
                    'pihak' => $value->pihak,
                    'tanggal_selesai' => $selesai->format('d/m/Y'),
                    'sisa_hari' => $sisa_hari,
                ];
            });

        return response()->json($data);
    }


    // Simpan
    public function store(Request $request)
    {
        $fileName = null;

        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');

            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/images/pks'), $fileName);
        }

        $query = Pks::create([
            'nomor_pks' => $request->nomor_pks,
            'nomor_kontrak' => $request->nomor_kontrak,
            'judul' => $request->judul,
            'id_jenis_kontrak' => $request->id_jenis_kontrak,
            'pihak' => $request->pihak,
            'tanggal_mulai' => $request->tanggal_mulai ? Carbon::createFromFormat('d/m/Y', $request->tanggal_mulai)->format('Y-m-d') : null,
            'tanggal_selesai' => $request->tanggal_selesai ? Carbon::createFromFormat('d/m/Y', $request->tanggal_selesai)->format('Y-m-d') : null,
            'file' => $fileName,
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


    // edit
    public function update(Request $request, $id)
    {
        $pks = Pks::find($id);

        if (!$pks) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $fileName = $pks->file; // default file lama

        if ($request->hasFile('lampiran')) {

            // hapus file lama
            if (!empty($pks->file)) {
                $oldFile = public_path('uploads/images/pks/' . $pks->file);
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            // upload file baru
            $file = $request->file('lampiran');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/images/pks'), $fileName);
        }

        $pks->update([
            'nomor_pks' => $request->nomor_pks,
            'nomor_kontrak' => $request->nomor_kontrak,
            'judul' => $request->judul,
            'id_jenis_kontrak' => $request->id_jenis_kontrak,
            'pihak' => $request->pihak,
            'tanggal_mulai' => $request->tanggal_mulai ? Carbon::createFromFormat('d/m/Y', $request->tanggal_mulai)->format('Y-m-d') : null,
            'tanggal_selesai' => $request->tanggal_selesai ? Carbon::createFromFormat('d/m/Y', $request->tanggal_selesai)->format('Y-m-d') : null,
            'file' => $fileName,
        ]);

        return response()->json([
            'success' => true,
            'data' => [],
            'message' => 'Data Berhasil Diupdate.',
        ], 200);
    }


    // Delete
    public function destroy($id)
    {
        $pks = Pks::find($id);

        if (!$pks) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }

        // hapus file jika ada
        if ($pks->file) {
            $filePath = public_path('uploads/images/pks/' . $pks->file);

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $pks->delete();

        return response()->json([
            'success' => true,
            'data' => [],
            'message' => 'Data Berhasil Dihapus.',
        ], 200);
    }


    // update status
    public function updateStatus(Request $request, $id)
    {
        $query = Pks::where('id', $id)->update([
            'status' => $request->status,
        ]);
        if ($query) {
            return response()->json([
                'success' => true,
                'message' => 'Sukses mengubah status menjadi ' . ($request->status === '1' ? 'Aktif' : 'Tidak Aktif'),
                'data' => [],
            ], status: 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status.',
                'data' => [],
            ], status: 400);
        }
    }

}
