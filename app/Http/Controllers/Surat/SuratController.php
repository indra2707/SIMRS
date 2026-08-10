<?php

namespace App\Http\Controllers\Surat;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Surat\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

        return view('surat.surat', [
            'data' => $data,
            'users' => $users,
        ]);
    }


    /**
     * Menampilkan data surat
     */
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

            $data[] = [
                'id' => $value->id,

                'tanggal' => $value->tanggal
                    ? date('d-m-Y', strtotime($value->tanggal))
                    : '-',

                'no_surat' => $value->no_surat,

                'approval_id' => $value->approval_id,

                'nama_approver' => $value->nama_approver ?? '-',

                'lampiran' => $value->lampiran,

                'perihal' => $value->perihal,

                'isi_surat' => $value->isi_surat,

                'created_at' => $value->created_at
                    ? date('d-m-Y H:i', strtotime($value->created_at))
                    : '-',
            ];
        }

        return response()->json($data, 200);
    }


    /**
     * Generate nomor surat
     */
    public function generateNoSurat(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
        ]);

        $tanggal = $request->tanggal;

        $tahun = date('Y', strtotime($tanggal));

        // Ambil nomor surat terakhir pada tahun tersebut
        $suratTerakhir = Surat::whereYear('tanggal', $tahun)
            ->orderBy('id', 'desc')
            ->first();

        if ($suratTerakhir) {

            // Ambil angka dari awal nomor surat
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


    /**
     * Simpan surat
     */
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

            'lampiran.image' => 'Lampiran harus berupa gambar.',
            'lampiran.mimes' => 'Lampiran hanya boleh JPG, JPEG, PNG atau WEBP.',
            'lampiran.max' => 'Ukuran lampiran maksimal 5 MB.',

            'perihal.required' => 'Perihal wajib diisi.',

            'isi_surat.required' => 'Isi surat wajib diisi.',
        ]);


        DB::beginTransaction();

        try {

            $lampiran = null;

            if ($request->hasFile('lampiran')) {

                $lampiran = $request->file('lampiran')->store(
                    'surat/lampiran',
                    'public'
                );
            }


            $surat = Surat::create([
                'tanggal' => $request->tanggal,

                'no_surat' => $request->no_surat,

                'approval_id' => $request->approval_id,

                'lampiran' => $lampiran,

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

            // Jika upload sudah terjadi tetapi database gagal
            if (!empty($lampiran)) {
                Storage::disk('public')->delete($lampiran);
            }

            return response()->json([
                'success' => false,
                'message' => 'Surat gagal ditambahkan.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Detail surat
     */
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


    /**
     * Update surat
     */
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
        ]);


        DB::beginTransaction();

        try {

            $lampiranBaru = null;


            /*
             * Jika upload lampiran baru
             */
            if ($request->hasFile('lampiran')) {

                $lampiranBaru = $request->file('lampiran')->store(
                    'surat/lampiran',
                    'public'
                );
            }


            /*
             * Update data
             */
            $surat->tanggal = $request->tanggal;

            $surat->no_surat = $request->no_surat;

            $surat->approval_id = $request->approval_id;

            $surat->perihal = $request->perihal;

            $surat->isi_surat = $request->isi_surat;


            /*
             * Jika ada lampiran baru
             */
            if ($lampiranBaru) {

                // Hapus file lama
                if ($surat->lampiran) {
                    Storage::disk('public')->delete(
                        $surat->lampiran
                    );
                }

                $surat->lampiran = $lampiranBaru;
            }


            $surat->save();

            DB::commit();


            return response()->json([
                'success' => true,
                'data' => $surat,
                'message' => 'Surat berhasil diubah.',
            ], 200);
        } catch (\Throwable $e) {

            DB::rollBack();

            if ($lampiranBaru) {
                Storage::disk('public')->delete(
                    $lampiranBaru
                );
            }

            return response()->json([
                'success' => false,
                'message' => 'Surat gagal diubah.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Hapus surat
     */
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

            /*
             * Hapus file lampiran
             */
            if ($surat->lampiran) {

                Storage::disk('public')->delete(
                    $surat->lampiran
                );
            }


            /*
             * Hapus database
             */
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


    /**
     * Update approval surat
     *
     * approval_id = users.id
     */
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
}
