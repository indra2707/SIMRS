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
    public function views(Request $request)
    {
        $query = Gaji::query();

        // FILTER BERDASARKAN BULAN / RANGE TANGGAL
        if ($request->tgl_awal && $request->tgl_akhir) {
            $query->whereBetween('bulan', [
                $request->tgl_awal,
                $request->tgl_akhir
            ]);
        }

        $data = [];
        foreach ($query->get() as $value) {
            $data[] = [
                'id' => $value->id,
                'nomor_pekerja' => $value->nomor_pekerja,
                'bulan' => Carbon::parse($value->bulan)->format('M Y'),
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

            $bulanFolder = Carbon::createFromFormat('d/m/Y', $request->bulan)->format('Y-m');

            $uploadDir = public_path("uploads/images/slip-gaji/{$bulanFolder}");
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $fileName = $zip->getNameIndex($i);

                if (str_ends_with($fileName, '/'))
                    continue;
                if (strtolower(pathinfo($fileName, PATHINFO_EXTENSION)) !== 'pdf')
                    continue;

                $baseFileName = basename($fileName);

                // ambil nomor pekerja dari belakang setelah "-"
                $namaTanpaExt = pathinfo($baseFileName, PATHINFO_FILENAME);
                $parts = explode('-', $namaTanpaExt);
                $namaPekerja = end($parts);


                $content = $zip->getFromIndex($i);
                if ($content === false) {
                    throw new \Exception("Gagal extract file: {$fileName}");
                }

                file_put_contents($uploadDir . '/' . $baseFileName, $content);

                Gaji::create([
                    'bulan' => Carbon::createFromFormat('d/m/Y', $request->bulan)->format('Y-m-d'),
                    'nomor_pekerja' => $namaPekerja,
                    // 'file' => $baseFileName,
                    'file' => "uploads/images/slip-gaji/{$bulanFolder}/{$baseFileName}",
                    'created_by' => Auth::user()->username
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

    // Delete Single
    public function delete(Request $request)
    {
        try {
            $gaji = Gaji::findOrFail($request->id);

            if ($gaji->file) {
                $relativePath = ltrim($gaji->file, '/');

                if (filter_var($relativePath, FILTER_VALIDATE_URL)) {
                    $relativePath = parse_url($relativePath, PHP_URL_PATH);
                    $relativePath = ltrim($relativePath, '/');
                }

                $filePath = public_path($relativePath);

                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $gaji->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data & file berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Delete All
    public function deleteMultiple(Request $request)
    {
        $request->validate([
            'ids' => 'required|array'
        ]);

        $dataGaji = Gaji::whereIn('id', $request->ids)->get();

        foreach ($dataGaji as $gaji) {
            if (!$gaji->file)
                continue;

            // Bersihkan path
            $relativePath = ltrim($gaji->file, '/');

            // Kalau tersimpan full URL
            if (filter_var($relativePath, FILTER_VALIDATE_URL)) {
                $relativePath = parse_url($relativePath, PHP_URL_PATH);
                $relativePath = ltrim($relativePath, '/');
            }

            $filePath = public_path($relativePath);

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        Gaji::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data & file PDF berhasil dihapus'
        ]);
    }
}
