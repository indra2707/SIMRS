<?php

namespace App\Http\Controllers\Sdm;

use App\Models\Sdm\Pegawai;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     * Includes searching
     */
    public function index(Request $request)
    {
        $data = [
            'title' => 'Pegawai',
            'menuTitle' => 'SDM',
            'menuSubtitle' => 'Pegawai',

        ];

        return view('sdm.pegawai.pegawai', $data);
    }

    public function views()
    {
        $query = Pegawai::all();
        $data = [];

        foreach ($query as $key => $value) {
            $data[] = [
                'id' => $value->id ?? '-',
                // Company & Position Info
                'anak_perusahaan' => $value->anak_perusahaan ?? '-',
                'rumah_sakit' => $value->rumah_sakit ?? '-',
                'nomor_sk_struktur' => $value->nomor_sk_struktur ?? '-',
                'jabatan' => $value->jabatan ?? '-',
                'penempatan' => $value->penempatan ?? '-',
                'lokasi_kerja' => $value->lokasi_kerja ?? '-',

                // Personal Info
                'nomor_pekerja' => $value->nomor_pekerja ?? '-',
                'nama_pekerja' => $value->nama_pekerja ?? '-',
                'jenis_kelamin' => $value->jenis_kelamin ?? '-',
                'agama' => $value->agama ?? '-',
                'nik' => $value->nik ?? '-',
                'status_pernikahan' => $value->status_pernikahan ?? '-',
                'golongan_darah' => $value->golongan_darah ?? '-',
                'disabilitas' => $value->disabilitas ?? '-',
                'tanggal_lahir' => $value->tanggal_lahir ?? '-',
                'tanggal_lahir_formatted' => $value->tanggal_lahir ? \Carbon\Carbon::parse($value->tanggal_lahir)->format('d M Y') : '-',

                // Employment Status
                'golongan_upah' => $value->golongan_upah ?? '-',
                'status_kepegawaian' => $value->status_kepegawaian ?? '-',
                'tmt_status_kepegawaian' => $value->tmt_status_kepegawaian ?? '-',
                'tmt_status_kepegawaian_formatted' => $value->tmt_status_kepegawaian ? \Carbon\Carbon::parse($value->tmt_status_kepegawaian)->format('d M Y') : '-',
                'tmt_pwtt' => $value->tmt_pwtt ?? '-',
                'tmt_pwtt_formatted' => $value->tmt_pwtt ? \Carbon\Carbon::parse($value->tmt_pwtt)->format('d M Y') : '-',
                'tmt_pwt' => $value->tmt_pwt ?? '-',
                'tmt_pwt_formatted' => $value->tmt_pwt ? \Carbon\Carbon::parse($value->tmt_pwt)->format('d M Y') : '-',
                'masa_kerja' => $value->masa_kerja ?? '-',
                'tanggal_akhir_kontrak' => $value->tanggal_akhir_kontrak ?? '-',
                'tanggal_akhir_kontrak_formatted' => $value->tanggal_akhir_kontrak ? \Carbon\Carbon::parse($value->tanggal_akhir_kontrak)->format('d M Y') : '-',

                // Function & Grade
                'fungsi' => $value->fungsi ?? '-',
                'sub_fungsi' => $value->sub_fungsi ?? '-',
                'tmt_jabatan' => $value->tmt_jabatan ?? '-',
                'tmt_jabatan_formatted' => $value->tmt_jabatan ? \Carbon\Carbon::parse($value->tmt_jabatan)->format('d M Y') : '-',
                'tmt_golongan_upah' => $value->tmt_golongan_upah ?? '-',
                'tmt_golongan_upah_formatted' => $value->tmt_golongan_upah ? \Carbon\Carbon::parse($value->tmt_golongan_upah)->format('d M Y') : '-',
                'penyetaraan_jabatan_ap' => $value->penyetaraan_jabatan_ap ?? '-',
                'penyetaraan_golongan_upah_ap' => $value->penyetaraan_golongan_upah_ap ?? '-',

                // Banking Info
                'nama_bank' => $value->nama_bank ?? '-',
                'nomor_rekening' => $value->nomor_rekening ?? '-',
                'nama_rekening' => $value->nama_rekening ?? '-',

                // Insurance & Tax
                'nomor_bpjstk' => $value->nomor_bpjstk ?? '-',
                'nomor_bpjskesehatan' => $value->nomor_bpjskesehatan ?? '-',
                'nomor_npwp' => $value->nomor_npwp ?? '-',

                // Contact Info
                'nomor_hp' => $value->nomor_hp ?? '-',
                'email' => $value->email ?? '-',
                'email_dinas' => $value->email_dinas ?? '-',
                'nomor_kontak_darurat' => $value->nomor_kontak_darurat ?? '-',
                'nama_kontak_darurat' => $value->nama_kontak_darurat ?? '-',
                'hubungan_kontak_darurat' => $value->hubungan_kontak_darurat ?? '-',

                // Address Info
                'alamat_ktp' => $value->alamat_ktp ?? '-',
                'alamat_npwp' => $value->alamat_npwp ?? '-',
                'alamat_domisili' => $value->alamat_domisili ?? '-',

                // Professional Licenses
                'nomor_str' => $value->nomor_str ?? '-',
                'str_seumur_hidup' => $value->str_seumur_hidup ?? '-',
                'masa_berlaku_str' => $value->masa_berlaku_str ?? '-',
                'masa_berlaku_str_formatted' => $value->masa_berlaku_str ? \Carbon\Carbon::parse($value->masa_berlaku_str)->format('d M Y') : '-',
                'nomor_sip' => $value->nomor_sip ?? '-',
                'masa_berlaku_sip' => $value->masa_berlaku_sip ?? '-',
                'masa_berlaku_sip_formatted' => $value->masa_berlaku_sip ? \Carbon\Carbon::parse($value->masa_berlaku_sip)->format('d M Y') : '-',
                'asuransi_profesi' => $value->asuransi_profesi ?? '-',
                'nomor_polis' => $value->nomor_polis ?? '-',
                'masa_berlaku_asuransi' => $value->masa_berlaku_asuransi ?? '-',
                'masa_berlaku_asuransi_formatted' => $value->masa_berlaku_asuransi ? \Carbon\Carbon::parse($value->masa_berlaku_asuransi)->format('d M Y') : '-',

                // Education
                'pend_diploma' => $value->pend_diploma ?? '-',
                'pend_s1' => $value->pend_s1 ?? '-',
                'pend_s2' => $value->pend_s2 ?? '-',
                'pend_s3' => $value->pend_s3 ?? '-',
                'kampus_terakhir' => $value->kampus_terakhir ?? '-',
                'jenjang_pendidikan_terakhir' => $value->jenjang_pendidikan_terakhir ?? '-',
                'keterangan' => $value->keterangan ?? '-',

                // System Info
                'input_by' => $value->input_by ?? '-',
                'input_date' => $value->input_date ?? '-',
                'input_date_formatted' => $value->input_date ? \Carbon\Carbon::parse($value->input_date)->format('d M Y') : '-',
                'update_by' => $value->update_by ?? '-',
                'update_date' => $value->update_date ?? '-',
                'update_date_formatted' => $value->update_date ? \Carbon\Carbon::parse($value->update_date)->format('d M Y') : '-',
                'temp_username' => $value->temp_username ?? '-',
                'username' => $value->username ?? '-',

            ];
        }

        // ✅ TAMBAHKAN INI!
        return response()->json($data);
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $this->validateData($request);
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/pegawai'), $filename);

            $validated['foto'] = $filename;
        }
        $data = Pegawai::create($validated);

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
        $data = Pegawai::find($id);

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
        $pegawai = Pegawai::find($id);

        if (!$pegawai) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        $validated = $this->validateData($request);
        if ($request->hasFile('foto')) {


            if ($pegawai->foto && file_exists(public_path('uploads/pegawai/' . $pegawai->foto))) {
                unlink(public_path('uploads/pegawai/' . $pegawai->foto));
            }

            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/pegawai'), $filename);

            $validated['foto'] = $filename;
        }


        $pegawai->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diperbarui',
            'data' => $pegawai,
        ]);
    }

    /**
     * Remove the specified resource.
     */
    public function destroy($id)
    {
        $item = Pegawai::find($id);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }
        if ($item->foto && file_exists(public_path('uploads/pegawai/'.$item->foto))) {
        unlink(public_path('uploads/pegawai/'.$item->foto));
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
            'foto' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    }
}
