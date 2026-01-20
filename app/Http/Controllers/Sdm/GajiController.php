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

            if ($zip->open(storage_path('app/' . $zipPath)) !== true) {
                throw new \Exception('ZIP tidak bisa dibuka');
            }

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $fileName = $zip->getNameIndex($i);
                if (str_ends_with($fileName, '/'))
                    continue;
                if (strtolower(pathinfo($fileName, PATHINFO_EXTENSION)) !== 'pdf')
                    continue;

                $namaPekerja = pathinfo($fileName, PATHINFO_FILENAME);
                $content = $zip->getFromIndex($i);
                if ($content === false) {
                    throw new \Exception("Gagal extract file: {$fileName}");
                }

                $path = "{$fileName}";
                Storage::disk('public')->put($path, $content);

                Gaji::create([
                    'bulan' => Carbon::createFromFormat('d/m/Y', $request->bulan)->format('Y-m-d'),
                    'nomor_pekerja' => $namaPekerja,
                    'file' => $path,
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
