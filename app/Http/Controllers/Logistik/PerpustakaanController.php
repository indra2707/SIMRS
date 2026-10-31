<?php
namespace App\Http\Controllers\Logistik;
use App\Http\Controllers\Controller;
use App\Models\Logistik\Perpustakaan;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class PerpustakaanController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title' => 'Perpustakaan Digital',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Perpustakaan Digital',
        ];
        return view('logistik.perpustakaan.perpustakaan', $data);
    }

    // View
    public function views()
    {
        $query = Perpustakaan::all();
        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id' => $value->id,
                'judul' => $value->judul,
                'kategori' => $value->kategori,
                'lampiran' => $value->lampiran,
                'status' => $value->status,
            ];
        }
        return response()->json($data, 200);
    }


    // Store
    public function store(Request $request)
    {
        $fileName = null;
        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');

            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/perpustakaan'), $fileName);
        }

        $idPegawai = $request->nama_pekerja ?: Session::get('nama_pekerja');
        $query = Perpustakaan::create([
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'status' => 'belum_verifikasi',
            'created_by' => $idPegawai,
            'lampiran' => $fileName,
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


}
