<?php

namespace App\Http\Controllers\Surat;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Surat\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Dompdf\Dompdf;

class SuratController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Surat',
            'menuTitle' => 'Administrasi',
            'menuSubtitle' => 'Surat',
        ];

        $users = User::where('status', 'aktif')
            ->orderBy('username', 'asc')
            ->get();

        return view('surat.list-surat.list-surat', [
            'data' => $data,
            'users' => $users,
        ]);
    }



    public function views()
    {
        $query = DB::table('surat')
            ->leftJoin('users', 'users.id', '=', 'surat.approval_id')
            ->select(
                'surat.id',
                'surat.tanggal',
                'surat.no_surat',
                'surat.approval_id',
                'surat.lampiran',
                'surat.perihal',
                'surat.isi_surat',
                'surat.created_at',
                'surat.updated_at',
                'users.username as nama_approver'
            )
            ->orderBy('surat.id', 'desc')
            ->get();

        $data = [];

        foreach ($query as $key => $value) {

            $lampiranArr = $value->lampiran ? json_decode($value->lampiran, true) : [];
            if (!is_array($lampiranArr)) {
                $lampiranArr = [];
            }

            $data[] = [
                'id' => $value->id,

                'tanggal' => $value->tanggal
                    ? date('d-m-Y', strtotime($value->tanggal))
                    : '-',

                'tanggal_raw' => $value->tanggal
                    ? date('Y-m-d', strtotime($value->tanggal))
                    : null,

                'no_surat' => $value->no_surat,

                'approval_id' => $value->approval_id,

                'nama_approver' => $value->nama_approver ?? '-',

                'lampiran' => $lampiranArr,
                'lampiran_count' => count($lampiranArr),

                'perihal' => $value->perihal,

                'isi_surat' => $value->isi_surat,

                'created_at' => $value->created_at
                    ? date('d-m-Y H:i', strtotime($value->created_at))
                    : '-',
            ];
        }

        return response()->json($data, 200);
    }



    public function generateNoSurat(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
        ]);

        $tanggal = $request->tanggal;

        $tahun = date('Y', strtotime($tanggal));

        $suratTerakhir = Surat::whereYear('tanggal', $tahun)
            ->orderBy('id', 'desc')
            ->first();

        if ($suratTerakhir) {

            preg_match('/^(\d+)/', $suratTerakhir->no_surat, $matches);

            $nomorUrut = isset($matches[1])
                ? ((int) $matches[1] + 1)
                : 1;
        } else {
            $nomorUrut = 1;
        }

        $nomorUrut = str_pad(
            $nomorUrut,
            3,
            '0',
            STR_PAD_LEFT
        );

        $bulan = date('m', strtotime($tanggal));

        $noSurat = $nomorUrut
            . '/SURAT/RSOJ/'
            . $bulan
            . '/'
            . $tahun;

        return response()->json([
            'success' => true,
            'no_surat' => $noSurat,
        ], 200);
    }



    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',

            'no_surat' => [
                'required',
                'string',
                'max:100',
                'unique:surat,no_surat',
            ],

            'approval_id' => [
                'nullable',
                'exists:users,id',
            ],

            'lampiran' => [
                'nullable',
                'array',
                'max:5',
            ],
            'lampiran.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'perihal' => [
                'required',
                'string',
                'max:255',
            ],

            'isi_surat' => [
                'required',
                'string',
            ],
        ], [
            'tanggal.required' => 'Tanggal surat wajib diisi.',

            'no_surat.required' => 'Nomor surat wajib diisi.',
            'no_surat.unique' => 'Nomor surat sudah digunakan.',

            'approval_id.exists' => 'User approval tidak ditemukan.',

            'lampiran.*.image' => 'Lampiran harus berupa gambar.',
            'lampiran.*.mimes' => 'Lampiran hanya boleh JPG, JPEG, PNG atau WEBP.',
            'lampiran.*.max' => 'Ukuran tiap lampiran maksimal 5 MB.',

            'perihal.required' => 'Perihal wajib diisi.',

            'isi_surat.required' => 'Isi surat wajib diisi.',
        ]);


        DB::beginTransaction();

        try {

            $lampiranPaths = [];

            if ($request->hasFile('lampiran')) {
                foreach ($request->file('lampiran') as $file) {
                    $lampiranPaths[] = $file->store('surat/lampiran', 'public');
                }
            }


            $surat = Surat::create([
                'tanggal' => $request->tanggal,

                'no_surat' => $request->no_surat,

                'approval_id' => $request->approval_id,

                'lampiran' => $lampiranPaths,

                'perihal' => $request->perihal,

                'isi_surat' => $request->isi_surat,
            ]);


            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $surat,
                'message' => 'Surat berhasil ditambahkan.',
            ], 200);
        } catch (\Throwable $e) {

            DB::rollBack();

            foreach ($lampiranPaths as $path) {
                Storage::disk('public')->delete($path);
            }

            return response()->json([
                'success' => false,
                'message' => 'Surat gagal ditambahkan.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function show($id)
    {
        $surat = Surat::with('approver')->find($id);

        if (!$surat) {

            return response()->json([
                'success' => false,
                'message' => 'Data surat tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $surat,
        ], 200);
    }



    public function update(Request $request, $id)
    {
        $surat = Surat::find($id);

        if (!$surat) {

            return response()->json([
                'success' => false,
                'message' => 'Data surat tidak ditemukan.',
            ], 404);
        }


        $request->validate([
            'tanggal' => 'required|date',

            'no_surat' => [
                'required',
                'string',
                'max:100',
                'unique:surat,no_surat,' . $id,
            ],

            'approval_id' => [
                'nullable',
                'exists:users,id',
            ],

            'lampiran' => [
                'nullable',
                'array',
                'max:5',
            ],
            'lampiran.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'hapus_lampiran' => [
                'nullable',
                'array',
            ],

            'perihal' => [
                'required',
                'string',
                'max:255',
            ],

            'isi_surat' => [
                'required',
                'string',
            ],
        ]);


        DB::beginTransaction();

        try {

            $lampiranBaru = [];

            if ($request->hasFile('lampiran')) {
                foreach ($request->file('lampiran') as $file) {
                    $lampiranBaru[] = $file->store('surat/lampiran', 'public');
                }
            }


            $surat->tanggal = $request->tanggal;
            $surat->no_surat = $request->no_surat;
            $surat->approval_id = $request->approval_id;
            $surat->perihal = $request->perihal;
            $surat->isi_surat = $request->isi_surat;


            $lampiranLama = $surat->lampiran ?? [];
            $hapusList = $request->input('hapus_lampiran', []);

            $lampiranTetap = array_values(array_filter(
                $lampiranLama,
                fn ($path) => !in_array($path, $hapusList)
            ));

            foreach ($hapusList as $path) {
                if (in_array($path, $lampiranLama)) {
                    Storage::disk('public')->delete($path);
                }
            }

            $surat->lampiran = array_merge($lampiranTetap, $lampiranBaru);


            $surat->save();

            DB::commit();


            return response()->json([
                'success' => true,
                'data' => $surat,
                'message' => 'Surat berhasil diubah.',
            ], 200);
        } catch (\Throwable $e) {

            DB::rollBack();

            foreach ($lampiranBaru as $path) {
                Storage::disk('public')->delete($path);
            }

            return response()->json([
                'success' => false,
                'message' => 'Surat gagal diubah.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function destroy($id)
    {
        $surat = Surat::find($id);

        if (!$surat) {

            return response()->json([
                'success' => false,
                'message' => 'Data surat tidak ditemukan.',
            ], 404);
        }


        DB::beginTransaction();

        try {

            foreach (($surat->lampiran ?? []) as $path) {
                Storage::disk('public')->delete($path);
            }


            $surat->delete();

            DB::commit();


            return response()->json([
                'success' => true,
                'message' => 'Surat berhasil dihapus.',
            ], 200);
        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Surat gagal dihapus.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function updateApproval(Request $request, $id)
    {
        $request->validate([
            'approval_id' => [
                'required',
                'exists:users,id',
            ],
        ], [
            'approval_id.required' => 'User approval wajib dipilih.',
            'approval_id.exists' => 'User approval tidak ditemukan.',
        ]);


        $surat = Surat::find($id);

        if (!$surat) {

            return response()->json([
                'success' => false,
                'message' => 'Data surat tidak ditemukan.',
            ], 404);
        }


        $surat->update([
            'approval_id' => $request->approval_id,
        ]);


        return response()->json([
            'success' => true,
            'message' => 'Approval surat berhasil diubah.',
            'data' => $surat,
        ], 200);
    }


    public function previewLampiran($id)
    {
        $surat = Surat::find($id);

        if (!$surat) {
            abort(404, 'Data surat tidak ditemukan.');
        }

        $lampiranList = [];

        foreach (($surat->lampiran ?? []) as $path) {
            $fullPath = storage_path('app/public/' . $path);

            if (file_exists($fullPath)) {
                $lampiranList[] = [
                    'base64' => base64_encode(file_get_contents($fullPath)),
                    'mime'   => strtolower(pathinfo($fullPath, PATHINFO_EXTENSION)) === 'jpg'
                        ? 'jpeg'
                        : strtolower(pathinfo($fullPath, PATHINFO_EXTENSION)),
                ];
            }
        }

        return view('surat.lampiran-preview', [
            'surat'        => $surat,
            'lampiranList' => $lampiranList,
        ]);
    }


    public function previewPdf($id)
    {
        $surat = Surat::with('approver')->find($id);

        if (!$surat) {
            abort(404, 'Data surat tidak ditemukan.');
        }

        $bgPath = public_path('assets/images/bg-surat.png');
        $bgBase64 = file_exists($bgPath) ? base64_encode(file_get_contents($bgPath)) : '';

        // ---- Isi surat dipecah per paragraf ----
        $isiSurat = strip_tags($surat->isi_surat);
        $paragrafIsi = array_values(array_filter(
            array_map('trim', explode("\n", $isiSurat)),
            fn ($p) => $p !== ''
        ));

        $lampiranEncoded = [];

        foreach (($surat->lampiran ?? []) as $path) {
            $fullPath = storage_path('app/public/' . $path);

            if (file_exists($fullPath)) {
                $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

                $ukuranAsli = @getimagesize($fullPath);
                $maxWidthPx = 450; // setara ~12cm
                $lebar = $maxWidthPx;
                if ($ukuranAsli && $ukuranAsli[0] < $maxWidthPx) {
                    $lebar = $ukuranAsli[0];
                }

                $lampiranEncoded[] = [
                    'base64' => base64_encode(file_get_contents($fullPath)),
                    'mime'   => $ext === 'jpg' ? 'jpeg' : $ext,
                    'width'  => $lebar,
                ];
            }
        }

        $html = view('surat.list-surat.preview', [
            'surat'       => $surat,
            'tanggal'     => Carbon::parse($surat->tanggal)->translatedFormat('d F Y'),
            'bgBase64'    => $bgBase64,
            'paragrafIsi' => $paragrafIsi,
            'lampiranList'=> $lampiranEncoded,
        ])->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('a4', 'portrait');
        $dompdf->render();

        $namaFile = 'Surat_' . str_replace(['/', '\\'], '-', $surat->no_surat) . '.pdf';

        return $dompdf->stream($namaFile, [
            'Attachment' => false,
        ]);
    }
}
