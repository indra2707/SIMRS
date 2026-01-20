<?php

namespace App\Http\Controllers\Sdm;

use App\Http\Controllers\Controller;
use App\Models\Sdm\Gaji;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB as Db;
use Illuminate\Support\Facades\Auth;

class GajiController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title' => 'Slip Gaji',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Slip Gaji',
        ];
        return view('sdm.gaji.gaji', $data);
    }

    // Views Table
    public function views()
    {
        $query = Gaji::all();

        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id' => $value->id,
                'nomor_pekerja' => $value->nomor_pekerja,
                'bulan' => $value->bulan,
                'file' => $value->file
            ];
        }
        return response()->json($data, 200);
    }

    // Simpan
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $gaji = Gaji::create([
                'nomor_pekerja' => $request->nomor_pekerja,
                'bulan' => Carbon::createFromFormat('d/m/Y', $request->bulan)->format('Y-m-d'),
                'file' => $request->file,
                'created_by' => Auth::user()->name
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $gaji,
                'message' => 'Data berhasil ditambahkan'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Data gagal ditambahkan',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // Delete
    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            // hapus tabel utama
            $deleteSpd = Spds::where('id', $id)->delete();

            // hapus tabel detail
            $deleteDetail = DB::table('tbl_spd_details')
                ->where('no_surat', $request->no_surat)
                ->delete();

            if ($deleteSpd === 0) {
                throw new \Exception('Data utama tidak ditemukan');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Data gagal dihapus',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
