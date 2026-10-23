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


    // Search
    public function search(Request $request)
    {
        $query = trim($request->input('query', ''));

        $results = Perpustakaan::query()
            ->when($query !== '', function ($q) use ($query) {
                $q->where('judul', 'LIKE', '%' . $query . '%');
            })
            ->orderBy('judul', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $results,
            'message' => $results->isNotEmpty()
                ? 'Data berhasil ditemukan.'
                : 'Data tidak ditemukan.',
        ], 200);
    }

}
