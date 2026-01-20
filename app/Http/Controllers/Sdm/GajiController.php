<?php

namespace App\Http\Controllers\Sdm;

use App\Http\Controllers\Controller;
use App\Models\Sdm\Gaji;
use Illuminate\Http\Request;
use ZipArchive;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB as Db;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
    // public function store(Request $request)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $gaji = Gaji::create([
    //             'nomor_pekerja' => $request->nomor_pekerja,
    //             'bulan' => Carbon::createFromFormat('d/m/Y', $request->bulan)->format('Y-m-d'),
    //             'file' => $request->file,
    //             'created_by' => Auth::user()->name
    //         ]);

    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'data' => $gaji,
    //             'message' => 'Data berhasil ditambahkan'
    //         ], 200);
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Data gagal ditambahkan',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function store(Request $request)
    {
        $request->validate([
            'file_zip' => 'required|file|mimes:zip',
            'bulan' => 'required|date_format:d/m/Y'
        ]);

        DB::beginTransaction();

        try {
            $zipFile = $request->file('file_zip');

            $zipPath = $zipFile->storeAs(
                'temp',
                time() . '_' . $zipFile->getClientOriginalName()
            );

            $zip = new ZipArchive;
            $zip->open(storage_path('app/' . $zipPath));

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $fileName = $zip->getNameIndex($i);

                // skip folder
                if (str_ends_with($fileName, '/'))
                    continue;

                // hanya PDF
                if (strtolower(pathinfo($fileName, PATHINFO_EXTENSION)) !== 'pdf')
                    continue;

                $namaPekerja = pathinfo($fileName, PATHINFO_FILENAME);
                $namaPekerjaClean = str_replace(' ', '_', $namaPekerja);

                $content = $zip->getFromIndex($i);

                $path = "gaji/{$namaPekerjaClean}/{$fileName}";
                Storage::put($path, $content);

                Gaji::create([
                    // 'nomor_pekerja' => $namaPekerjaClean,
                    'bulan' => Carbon::createFromFormat('d/m/Y', $request->bulan)->format('Y-m-d'),
                    'nomor_pekerja' => $fileName, // SIMPAN NAMA FILE
                    'file' => $path,     // SIMPAN PATH
                    'created_by' => Auth::user()->name
                ]);
            }

            $zip->close();
            Storage::delete($zipPath);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Import ZIP PDF berhasil'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Delete All
    public function deleteMultiple(Request $request)
    {
        Gaji::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}
