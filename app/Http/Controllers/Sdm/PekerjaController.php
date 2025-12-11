<?php

namespace App\Http\Controllers;

use App\Models\Sdm\Pekerja;
use Illuminate\Http\Request;

class MasterPekerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     * Includes searching
     */
    public function index(Request $request)
    {
        $query = Pekerja::query();

        // Searching (nama / nomor pekerja / nik)
        if ($request->search) {
            $query->where('nama_pekerja', 'like', '%' . $request->search . '%')
                  ->orWhere('nomor_pekerja', 'like', '%' . $request->search . '%')
                  ->orWhere('nik', 'like', '%' . $request->search . '%');
        }

        return response()->json([
            'success' => true,
            'data' => $query->paginate(20)
        ]);
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        $data = Pekerja::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dibuat',
            'data' => $data,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = Pekerja::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, $id)
    {
        $item = Pekerja::find($id);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        $validated = $this->validateData($request);

        $item->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diperbarui',
            'data' => $item,
        ]);
    }

    /**
     * Remove the specified resource.
     */
    public function destroy($id)
    {
        $item = Pekerja::find($id);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }


    /**
     * PRIVATE FUNCTION:
     * Validasi otomatis semua kolom model
     */
    private function validateData(Request $request)
    {
        return $request->validate([
            'anak_perusahaan' => 'nullable|string',
            'rumah_sakit' => 'nullable|string',
            'nomor_sk_struktur' => 'nullable|string',
            'jabatan' => 'nullable|string',
            'penempatan' => 'nullable|string',
            'lokasi_kerja' => 'nullable|string',
            'nomor_pekerja' => 'nullable|string',
            'nama_pekerja' => 'required|string|max:255',
            'jenis_kelamin' => 'nullable|string',
            'agama' => 'nullable|string',
            'nik' => 'nullable|string',
            'status_pernikahan' => 'nullable|string',
            'golongan_darah' => 'nullable|string',
            'disabilitas' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',

            'golongan_upah' => 'nullable|string',
            'status_kepegawaian' => 'nullable|string',
            'tmt_status_kepegawaian' => 'nullable|date',
            'tmt_pwtt' => 'nullable|date',
            'tmt_pwt' => 'nullable|date',
            'masa_kerja' => 'nullable|string',

            'fungsi' => 'nullable|string',
            'sub_fungsi' => 'nullable|string',
            'tmt_jabatan' => 'nullable|date',
            'tmt_golongan_upah' => 'nullable|date',
            'penyetaraan_jabatan_ap' => 'nullable|string',
            'penyetaraan_golongan_upah_ap' => 'nullable|string',

            'nama_bank' => 'nullable|string',
            'nomor_rekening' => 'nullable|string',
            'nama_rekening' => 'nullable|string',
            'nomor_bpjstk' => 'nullable|string',
            'nomor_bpjskesehatan' => 'nullable|string',
            'nomor_npwp' => 'nullable|string',

            'nomor_hp' => 'nullable|string',
            'nomor_kontak_darurat' => 'nullable|string',
            'nama_kontak_darurat' => 'nullable|string',
            'hubungan_kontak_darurat' => 'nullable|string',

            'email' => 'nullable|email',
            'email_dinas' => 'nullable|string',

            'alamat_ktp' => 'nullable|string',
            'alamat_npwp' => 'nullable|string',
            'alamat_domisili' => 'nullable|string',

            'nomor_str' => 'nullable|string',
            'str_seumur_hidup' => 'nullable|string',
            'masa_berlaku_str' => 'nullable|date',

            'nomor_sip' => 'nullable|string',
            'masa_berlaku_sip' => 'nullable|date',

            'asuransi_profesi' => 'nullable|string',
            'nomor_polis' => 'nullable|string',
            'masa_berlaku_asuransi' => 'nullable|date',

            'pend_diploma' => 'nullable|string',
            'pend_s1' => 'nullable|string',
            'pend_s2' => 'nullable|string',
            'pend_s3' => 'nullable|string',
            'kampus_terakhir' => 'nullable|string',

            'keterangan' => 'nullable|string',
            'tanggal_akhir_kontrak' => 'nullable|date',

            'jenjang_pendidikan_terakhir' => 'nullable|string',

            'input_by' => 'nullable|string',
            'input_date' => 'nullable|date',
            'update_by' => 'nullable|string',
            'update_date' => 'nullable|date',

            'temp_username' => 'nullable|string',
            'username' => 'nullable|string',
        ]);
    }
}
