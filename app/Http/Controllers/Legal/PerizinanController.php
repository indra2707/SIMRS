<?php

namespace App\Http\Controllers\Legal;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Legal\Perizinan;
use App\Http\Controllers\Controller;

class PerizinanController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Perizinan',
            'menuTitle' => 'Legal',
            'menuSubtitle' => 'Perizinan',
        ];
        return view('legal.perizinan.perizinan', $data);
    }

    public function views(Request $request)
    {
        $query = Perizinan::query();

        // FILTER RANGE TANGGAL
        if ($request->filled('tgl_awal') && $request->filled('tgl_akhir')) {
            $query->where(function ($q) use ($request) {
                $q->whereBetween('tgl_awal', [
                    $request->tgl_awal,
                    $request->tgl_akhir
                ])
                    ->orWhereNull('tgl_awal');
            });
        }

        // FILTER STATUS
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $query->orderBy('tgl_awal', 'desc');

        $data = [];

        foreach ($query->get() as $value) {

            $sisa_hari = 0;

            if ($value->tgl_akhir) {
                $today = Carbon::today();
                $selesai = Carbon::parse($value->tgl_akhir);

                $sisa_hari = max(0, $today->diffInDays($selesai, false));
            }

            $data[] = [
                'id' => $value->id,
                'nomor_perizinan' => $value->nomor_perizinan,
                'jenis_perizinan' => $value->jenis_perizinan,
                'upload' => $value->upload,
                'status' => $value->status,
                'tgl_awal' => $value->tgl_awal
                    ? Carbon::parse($value->tgl_awal)->format('d/m/Y')
                    : null,
                'tgl_akhir' => $value->tgl_akhir
                    ? Carbon::parse($value->tgl_akhir)->format('d/m/Y')
                    : null,
                'sisa_hari' => $sisa_hari,
            ];
        }

        return response()->json($data, 200);
    }


    public function store(Request $request)
    {
        if (!is_dir('uploads/legal/perizinan/')) {
            mkdir('uploads/legal/perizinan/', 0777, true);
        }

        $validate = $request->validate([
            'nomor_perizinan' => 'required',
            'jenis_perizinan' => 'required',
            'tgl_awal' => 'required',
            'tgl_akhir' => 'required',
            'upload' => 'required|file',
        ]);
        $file = $request->file('upload');
        if ($file != null) {
            $extension = $file->getClientOriginalExtension();
            $filename = $request->nomor_perizinan . '-' . strtolower(string: str_replace(' ', '_', $request->jenis_perizinan)) . '_' . time() . '.' . $extension;
            $path = 'uploads/legal/perizinan/';
            $file->move($path, $filename);
            $validate['upload'] = $filename;
        }
        $validate['tgl_awal'] = $request->tgl_awal ? Carbon::createFromFormat('d/m/Y', $request->tgl_awal)->format('Y-m-d') : null;
        $validate['tgl_akhir'] = $request->tgl_akhir ? Carbon::createFromFormat('d/m/Y', $request->tgl_akhir)->format('Y-m-d') : null;
        $perizinan = Perizinan::create($validate);
        if ($perizinan) {
            return response()->json([
                'message' => 'Data Berhasil Ditambahkan.',
                'data' => $perizinan,
                'success' => 'true'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data Gagal Ditambahkan.',
                'data' => [],
                'success' => 'false'
            ], 400);
        }
    }

    public function update(Request $request, $id)
    {
        $perizinan = Perizinan::find($id);

        if (!$perizinan) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $fileName = $perizinan->upload;

        if ($request->hasFile('upload')) {

            if (!empty($perizinan->upload)) {
                $oldFile = public_path('uploads/legal/perizinan/' . $perizinan->upload);
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            $file = $request->file('upload');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/legal/perizinan'), $fileName);
        }

        $perizinan->update([
            'nomor_perizinan' => $request->nomor_perizinan,
            'jenis_perizinan' => $request->jenis_perizinan,
            'status' => $request->status,
            'tgl_awal' => $request->tgl_awal ? Carbon::createFromFormat('d/m/Y', $request->tgl_awal)->format('Y-m-d') : null,
            'tgl_akhir' => $request->tgl_akhir ? Carbon::createFromFormat('d/m/Y', $request->tgl_akhir)->format('Y-m-d') : null,
            'upload' => $fileName,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Diupdate.',
        ], 200);
    }

    public function destroy($id)
    {
        $perizinan = Perizinan::find($id);

        if (!$perizinan) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        if (!empty($perizinan->upload)) {
            $oldFile = public_path('uploads/legal/perizinan/' . $perizinan->upload);
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        $perizinan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus.'
        ], 200);
    }

    public function notify()
    {
        $today = Carbon::today();
        $batas = Carbon::today()->addDays(60);

        $data = Perizinan::query()
            ->from('perizinan')
            ->select(
                'perizinan.*'
            )
            ->whereNotNull('perizinan.tgl_akhir')
            ->whereDate('perizinan.tgl_akhir', '>=', $today)
            ->whereDate('perizinan.tgl_akhir', '<=', $batas)
            ->orderBy('perizinan.tgl_akhir', 'asc')
            ->get()
            ->map(function ($value) use ($today) {

                $selesai = Carbon::parse($value->tgl_akhir);
                $sisa_hari = $today->diffInDays($selesai, false);

                return [
                    'id' => $value->id,
                    'nomor_perizinan' => $value->nomor_perizinan,
                    'jenis_perizinan' => $value->jenis_perizinan,
                    'status' => $value->statuss,
                    'tgl_akhir' => $selesai->format('d/m/Y'),
                    'sisa_hari' => $sisa_hari,
                ];
            });

        return response()->json($data);
    }
}
