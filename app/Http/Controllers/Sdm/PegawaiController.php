<?php

namespace App\Http\Controllers\Sdm;

use App\Models\Sdm\Pegawai;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        //
        $query = DB::table('pegawai')
            ->join('tbl_sk_struktur', 'tbl_sk_struktur.id', '=', 'pegawai.id_sk_struktur')
            ->join('tbl_jabatan', 'tbl_jabatan.id', '=', 'pegawai.id_jabatan')
            ->join('tbl_fungsi', 'tbl_fungsi.id', '=', 'pegawai.id_sub_fungsi')
            ->join('tbl_bank', 'tbl_bank.id', '=', 'pegawai.id_bank')
            ->select(
                'pegawai.*',
                'tbl_sk_struktur.no_sk as no_sk_struktur',
                'tbl_jabatan.nama_jabatan as nama_jabatan',
                'tbl_jabatan.unit as nama_rumah_sakit',
                'tbl_fungsi.nama_fungsi as nama_fungsi',
                'tbl_bank.nama_bank as nama_bank'
            )
            ->get();
        $data = [];

        foreach ($query as $key => $value) {
            $data[] = [
                'id' => $value->id ?? null,
                'id_sk_struktur' => $value->id_sk_struktur ?? null,
                'id_jabatan' => $value->id_jabatan ?? null,
                'id_sub_fungsi' => $value->id_sub_fungsi ?? null,
                'id_bank' => $value->id_bank ?? null,
                // Company & Position Info
                'anak_perusahaan' => $value->anak_perusahaan ?? null,
                'nomor_sk_struktur' => $value->nomor_sk_struktur ?? null,
                'no_sk_struktur' => $value->no_sk_struktur ?? null,
                'jabatan' => $value->jabatan ?? null,
                'nama_jabatan' => $value->nama_jabatan ?? null,
                'penempatan' => $value->penempatan ?? null,
                'lokasi_kerja' => $value->lokasi_kerja ?? null,
                'nama_rumah_sakit' => $value->nama_rumah_sakit ?? null,

                // Personal Info
                'nomor_pekerja' => $value->nomor_pekerja ?? null,
                'nama_pekerja' => $value->nama_pekerja ?? null,
                'jenis_kelamin' => $value->jenis_kelamin ?? null,
                'agama' => $value->agama ?? null,
                'nik' => $value->nik ?? null,
                'status_pernikahan' => $value->status_pernikahan ?? null,
                'golongan_darah' => $value->golongan_darah ?? null,
                'disabilitas' => $value->disabilitas ?? null,
                'tanggal_lahir' => $value->tanggal_lahir ?? null,
                'tanggal_lahir_formatted' => $value->tanggal_lahir ? Carbon::parse($value->tanggal_lahir)->format('d M Y') : null,

                // Employment Status
                'golongan_upah' => $value->golongan_upah ?? null,
                'status_kepegawaian' => $value->status_kepegawaian ?? null,
                'tmt_status_kepegawaian' => $value->tmt_status_kepegawaian ?? null,
                'tmt_status_kepegawaian_formatted' => $value->tmt_status_kepegawaian ? Carbon::parse($value->tmt_status_kepegawaian)->format('d M Y') : null,
                'tmt_pwtt' => $value->tmt_pwtt ?? null,
                'tmt_pwtt_formatted' => $value->tmt_pwtt ? Carbon::parse($value->tmt_pwtt)->format('d M Y') : null,
                'tmt_pwt' => $value->tmt_pwt ?? null,
                'tmt_pwt_formatted' => $value->tmt_pwt ? Carbon::parse($value->tmt_pwt)->format('d M Y') : null,
                'masa_kerja' => $value->masa_kerja ?? null,
                'tanggal_akhir_kontrak' => $value->tanggal_akhir_kontrak ?? null,
                'tanggal_akhir_kontrak_formatted' => $value->tanggal_akhir_kontrak ? Carbon::parse($value->tanggal_akhir_kontrak)->format('d M Y') : null,

                // Function & Grade
                'fungsi' => $value->fungsi ?? null,
                'id_sub_fungsi' => $value->id_sub_fungsi ?? null,
                'nama_fungsi' => $value->nama_fungsi ?? null,
                'tmt_jabatan' => $value->tmt_jabatan ?? null,
                'tmt_jabatan_formatted' => $value->tmt_jabatan ? Carbon::parse($value->tmt_jabatan)->format('d M Y') : null,
                'tmt_golongan_upah' => $value->tmt_golongan_upah ?? null,
                'tmt_golongan_upah_formatted' => $value->tmt_golongan_upah ? Carbon::parse($value->tmt_golongan_upah)->format('d M Y') : null,
                'penyetaraan_jabatan_ap' => $value->penyetaraan_jabatan_ap ?? null,
                'penyetaraan_golongan_upah_ap' => $value->penyetaraan_golongan_upah_ap ?? null,

                // Banking Info
                'id_bank' => $value->id_bank ?? null,
                'nama_bank' => $value->nama_bank ?? null,
                'nomor_rekening' => $value->nomor_rekening ?? null,
                'nama_rekening' => $value->nama_rekening ?? null,

                // Insurance & Tax
                'nomor_bpjstk' => $value->nomor_bpjstk ?? null,
                'nomor_bpjskesehatan' => $value->nomor_bpjskesehatan ?? null,
                'nomor_npwp' => $value->nomor_npwp ?? null,

                // Contact Info
                'nomor_hp' => $value->nomor_hp ?? null,
                'email' => $value->email ?? null,
                'email_dinas' => $value->email_dinas ?? null,
                'nomor_kontak_darurat' => $value->nomor_kontak_darurat ?? null,
                'nama_kontak_darurat' => $value->nama_kontak_darurat ?? null,
                'hubungan_kontak_darurat' => $value->hubungan_kontak_darurat ?? null,

                // Address Info
                'alamat_ktp' => $value->alamat_ktp ?? null,
                'alamat_npwp' => $value->alamat_npwp ?? null,
                'alamat_domisili' => $value->alamat_domisili ?? null,

                // Professional Licenses
                'nomor_str' => $value->nomor_str ?? null,
                'str_seumur_hidup' => $value->str_seumur_hidup ?? null,
                'masa_berlaku_str' => $value->masa_berlaku_str ?? null,
                'masa_berlaku_str_formatted' => $value->masa_berlaku_str ? Carbon::parse($value->masa_berlaku_str)->format('d M Y') : null,
                'nomor_sip' => $value->nomor_sip ?? null,
                'masa_berlaku_sip' => $value->masa_berlaku_sip ?? null,
                'masa_berlaku_sip_formatted' => $value->masa_berlaku_sip ? Carbon::parse($value->masa_berlaku_sip)->format('d M Y') : null,
                'asuransi_profesi' => $value->asuransi_profesi ?? null,
                'nomor_polis' => $value->nomor_polis ?? null,
                'masa_berlaku_asuransi' => $value->masa_berlaku_asuransi ?? null,
                'masa_berlaku_asuransi_formatted' => $value->masa_berlaku_asuransi ? Carbon::parse($value->masa_berlaku_asuransi)->format('d M Y') : null,

                // Education
                'pend_diploma' => $value->pend_diploma ?? null,
                'pend_s1' => $value->pend_s1 ?? null,
                'pend_s2' => $value->pend_s2 ?? null,
                'pend_s3' => $value->pend_s3 ?? null,
                'kampus_terakhir' => $value->kampus_terakhir ?? null,
                'jenjang_pendidikan_terakhir' => $value->jenjang_pendidikan_terakhir ?? null,
                'keterangan' => $value->keterangan ?? null,

                // System Info
                'foto' => $value->foto ?? null,
            ];
        }

        return response()->json($data);
    }

    // Simpan
    public function store(Request $request)
    {

        $dataValues = [
            'anak_perusahaan' => $request->anak_perusahaan,
            'id_sk_struktur' => $request->id_sk_struktur,
            'id_jabatan' => $request->id_jabatan,
            'penempatan' => $request->penempatan,
            'lokasi_kerja' => $request->lokasi_kerja,
            'status_kepegawaian' => $request->status_kepegawaian,
            'nomor_pekerja' => $request->nomor_pekerja,
            'nama_pekerja' => $request->nama_pekerja,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama' => $request->agama,
            'nik' => $request->nik ? str_replace(' ', '', $request->nik) : null,
            'status_pernikahan' => $request->status_pernikahan,
            'golongan_darah' => $request->golongan_darah,
            'disabilitas' => $request->disabilitas,
            'tanggal_lahir' => convertDmyToYmd($request->tanggal_lahir),
            'golongan_upah' => $request->golongan_upah,
            'tmt_status_kepegawaian' => convertDmyToYmd($request->tmt_status_kepegawaian),
            'tmt_pwtt' => convertDmyToYmd($request->tmt_pwtt),
            'tmt_pwt' => convertDmyToYmd($request->tmt_pwt),
            'masa_kerja' => $request->masa_kerja,
            'fungsi' => $request->fungsi,
            'id_sub_fungsi' => $request->id_sub_fungsi,
            'tmt_jabatan' => convertDmyToYmd($request->tmt_jabatan),
            'tmt_golongan_upah' => convertDmyToYmd($request->tmt_golongan_upah),
            'penyetaraan_jabatan_ap' => $request->penyetaraan_jabatan_ap,
            'penyetaraan_golongan_upah_ap' => $request->penyetaraan_golongan_upah_ap,
            'id_bank' => $request->id_bank,
            'nomor_rekening' => $request->nomor_rekening ? str_replace(' ', '', $request->nomor_rekening) : null,
            'nama_rekening' => $request->nama_rekening,
            'nomor_bpjstk' => $request->nomor_bpjstk ? str_replace(' ', '', $request->nomor_bpjstk) : null,
            'nomor_bpjskesehatan' => $request->nomor_bpjskesehatan ? str_replace(' ', '', $request->nomor_bpjskesehatan) : null,
            'nomor_npwp' => $request->nomor_npwp ? str_replace(' ', '', $request->nomor_npwp) : null,
            'nomor_hp' => $request->nomor_hp ? str_replace(' ', '', $request->nomor_hp) : null,
            'nomor_kontak_darurat' => $request->nomor_kontak_darurat ? str_replace(' ', '', $request->nomor_kontak_darurat) : null,
            'nama_kontak_darurat' => $request->nama_kontak_darurat,
            'hubungan_kontak_darurat' => $request->hubungan_kontak_darurat,
            'email' => $request->email,
            'email_dinas' => $request->email_dinas,
            'alamat_ktp' => $request->alamat_ktp,
            'alamat_npwp' => $request->alamat_npwp,
            'alamat_domisili' => $request->alamat_domisili,
            'nomor_str' => $request->nomor_str,
            'str_seumur_hidup' => $request->str_seumur_hidup,
            'masa_berlaku_str' => convertDmyToYmd($request->masa_berlaku_str),
            'nomor_sip' => $request->nomor_sip,
            'masa_berlaku_sip' => convertDmyToYmd($request->masa_berlaku_sip),
            'asuransi_profesi' => $request->asuransi_profesi,
            'nomor_polis' => $request->nomor_polis,
            'masa_berlaku_asuransi' => convertDmyToYmd($request->masa_berlaku_asuransi),
            'pend_diploma' => $request->pend_diploma,
            'pend_s1' => $request->pend_s1,
            'pend_s2' => $request->pend_s2,
            'pend_s3' => $request->pend_s3,
            'kampus_terakhir' => $request->kampus_terakhir,
            'jenjang_pendidikan_terakhir' => $request->jenjang_pendidikan_terakhir,
            'keterangan' => $request->keterangan,
            'tanggal_akhir_kontrak' => convertDmyToYmd($request->tanggal_akhir_kontrak),
            'created_by' => auth()->user()->username ?? null,
            'created_at' => now()->format('Y-m-d H:i:s'),
        ];

        $file = $request->file('foto');
        if ($file != null) {
            $extension = $file->getClientOriginalExtension();
            $filename = 'Pegawai_' . strtolower(string: str_replace(' ', '_', $request->nama_pekerja)) . '_' . time() . '.' . $extension;
            $path = 'uploads/images/foto-pegawai/';
            $file->move($path, $filename);
            $dataValues['foto'] = $filename;
        }
        $query = Pegawai::create($dataValues);
        if ($query) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Data Berhasil Ditambahkan.',
            ], status: 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Data Gagal Ditambahkan.',
            ], status: 400);
        }
    }

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

    // Update
    public function update(Request $request, $id)
    {
        $dataValues = [
            'anak_perusahaan' => $request->anak_perusahaan,
            'id_sk_struktur' => $request->id_sk_struktur,
            'id_jabatan' => $request->id_jabatan,
            'penempatan' => $request->penempatan,
            'lokasi_kerja' => $request->lokasi_kerja,
            'status_kepegawaian' => $request->status_kepegawaian,
            'nomor_pekerja' => $request->nomor_pekerja,
            'nama_pekerja' => $request->nama_pekerja,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama' => $request->agama,
            'nik' => $request->nik ? str_replace(' ', '', $request->nik) : null,
            'status_pernikahan' => $request->status_pernikahan,
            'golongan_darah' => $request->golongan_darah,
            'disabilitas' => $request->disabilitas,
            'tanggal_lahir' => convertDmyToYmd($request->tanggal_lahir),
            'golongan_upah' => $request->golongan_upah,
            'tmt_status_kepegawaian' => convertDmyToYmd($request->tmt_status_kepegawaian),
            'tmt_pwtt' => convertDmyToYmd($request->tmt_pwtt),
            'tmt_pwt' => convertDmyToYmd($request->tmt_pwt),
            'masa_kerja' => $request->masa_kerja,
            'fungsi' => $request->fungsi,
            'id_sub_fungsi' => $request->id_sub_fungsi,
            'tmt_jabatan' => convertDmyToYmd($request->tmt_jabatan),
            'tmt_golongan_upah' => convertDmyToYmd($request->tmt_golongan_upah),
            'penyetaraan_jabatan_ap' => $request->penyetaraan_jabatan_ap,
            'penyetaraan_golongan_upah_ap' => $request->penyetaraan_golongan_upah_ap,
            'id_bank' => $request->id_bank,
            'nomor_rekening' => $request->nomor_rekening ? str_replace(' ', '', $request->nomor_rekening) : null,
            'nama_rekening' => $request->nama_rekening,
            'nomor_bpjstk' => $request->nomor_bpjstk ? str_replace(' ', '', $request->nomor_bpjstk) : null,
            'nomor_bpjskesehatan' => $request->nomor_bpjskesehatan ? str_replace(' ', '', $request->nomor_bpjskesehatan) : null,
            'nomor_npwp' => $request->nomor_npwp ? str_replace(' ', '', $request->nomor_npwp) : null,
            'nomor_hp' => $request->nomor_hp ? str_replace(' ', '', $request->nomor_hp) : null,
            'nomor_kontak_darurat' => $request->nomor_kontak_darurat ? str_replace(' ', '', $request->nomor_kontak_darurat) : null,
            'nama_kontak_darurat' => $request->nama_kontak_darurat,
            'hubungan_kontak_darurat' => $request->hubungan_kontak_darurat,
            'email' => $request->email,
            'email_dinas' => $request->email_dinas,
            'alamat_ktp' => $request->alamat_ktp,
            'alamat_npwp' => $request->alamat_npwp,
            'alamat_domisili' => $request->alamat_domisili,
            'nomor_str' => $request->nomor_str,
            'str_seumur_hidup' => $request->str_seumur_hidup,
            'masa_berlaku_str' => convertDmyToYmd($request->masa_berlaku_str),
            'nomor_sip' => $request->nomor_sip,
            'masa_berlaku_sip' => convertDmyToYmd($request->masa_berlaku_sip),
            'asuransi_profesi' => $request->asuransi_profesi,
            'nomor_polis' => $request->nomor_polis,
            'masa_berlaku_asuransi' => convertDmyToYmd($request->masa_berlaku_asuransi),
            'pend_diploma' => $request->pend_diploma,
            'pend_s1' => $request->pend_s1,
            'pend_s2' => $request->pend_s2,
            'pend_s3' => $request->pend_s3,
            'kampus_terakhir' => $request->kampus_terakhir,
            'jenjang_pendidikan_terakhir' => $request->jenjang_pendidikan_terakhir,
            'keterangan' => $request->keterangan,
            'tanggal_akhir_kontrak' => convertDmyToYmd($request->tanggal_akhir_kontrak),
            'updated_by' => auth()->user()->username ?? null,
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ];

        $file = $request->file('foto');
        if ($file != null) {
            $extension = $file->getClientOriginalExtension();
            $filename = 'Pegawai_' . strtolower(string: str_replace(' ', '_', $request->nama)) . '_' . time() . '.' . $extension;
            $path = 'uploads/images/foto-pegawai/';
            $file->move($path, $filename);

            // Hapus Foto Lama
            $data = Pegawai::where('id', $id)->first();
            if ($data->foto != null) {
                if (file_exists($path)) {
                    unlink($path . $data->foto);
                }
            }
            $dataValues['foto'] = $filename;
        }
        $query = Pegawai::where('id', $id)->update($dataValues);
        if ($query) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Data Berhasil Diubah.',
            ], status: 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Data Gagal Diubah.',
            ], status: 400);
        }
    }

    public function destroy($id)
    {
        try {
            $item = Pegawai::find($id);

            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan',
                ], 404);
            }

            // Hapus foto jika ada
            if ($item->foto && file_exists('uploads/images/foto-pegawai/' . $item->foto)) {
                unlink('uploads/images/foto-pegawai/' . $item->foto);
            }

            $item->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function prepareDataValues($request)
    {
        return [
            'anak_perusahaan' => $request->anak_perusahaan,
            'nomor_sk_struktur' => $request->nomor_sk_struktur,
            'jabatan' => $request->jabatan,
            'penempatan' => $request->penempatan,
            'lokasi_kerja' => $request->lokasi_kerja,

            'nomor_pekerja' => $request->nomor_pekerja,
            'nama_pekerja' => $request->nama_pekerja, // FIELD REQUIRED
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama' => $request->agama,
            'nik' => $request->nik ? str_replace(' ', '', $request->nik) : null,
            'status_pernikahan' => $request->status_pernikahan,
            'golongan_darah' => $request->golongan_darah,
            'disabilitas' => $request->disabilitas,

            // DATE
            'tanggal_lahir' => convertDmyToYmd($request->tanggal_lahir),

            'golongan_upah' => $request->golongan_upah,
            'status_kepegawaian' => $request->status_kepegawaian,

            // DATE
            'tmt_status_kepegawaian' => convertDmyToYmd($request->tmt_status_kepegawaian),
            'tmt_pwtt' => convertDmyToYmd($request->tmt_pwtt),
            'tmt_pwt' => convertDmyToYmd($request->tmt_pwt),

            'masa_kerja' => $request->masa_kerja,
            'fungsi' => $request->fungsi,
            'sub_fungsi' => $request->sub_fungsi,

            // DATE
            'tmt_jabatan' => convertDmyToYmd($request->tmt_jabatan),
            'tmt_golongan_upah' => convertDmyToYmd($request->tmt_golongan_upah),

            'penyetaraan_jabatan_ap' => $request->penyetaraan_jabatan_ap,
            'penyetaraan_golongan_upah_ap' => $request->penyetaraan_golongan_upah_ap,

            'nama_bank' => $request->nama_bank,
            'nomor_rekening' => $request->nomor_rekening ? str_replace(' ', '', $request->nomor_rekening) : null,
            'nama_rekening' => $request->nama_rekening,
            'nomor_bpjstk' => $request->nomor_bpjstk ? str_replace(' ', '', $request->nomor_bpjstk) : null,
            'nomor_bpjskesehatan' => $request->nomor_bpjskesehatan ? str_replace(' ', '', $request->nomor_bpjskesehatan) : null,
            'nomor_npwp' => $request->nomor_npwp ? str_replace(' ', '', $request->nomor_npwp) : null,

            'nomor_hp' => $request->nomor_hp ? str_replace(' ', '', $request->nomor_hp) : null,
            'nomor_kontak_darurat' => $request->nomor_kontak_darurat ? str_replace(' ', '', $request->nomor_kontak_darurat) : null,
            'nama_kontak_darurat' => $request->nama_kontak_darurat,
            'hubungan_kontak_darurat' => $request->hubungan_kontak_darurat,

            'email' => $request->email,
            'email_dinas' => $request->email_dinas,

            'alamat_ktp' => $request->alamat_ktp,
            'alamat_npwp' => $request->alamat_npwp,
            'alamat_domisili' => $request->alamat_domisili,

            'nomor_str' => $request->nomor_str,
            'str_seumur_hidup' => $request->str_seumur_hidup,

            // DATE
            'masa_berlaku_str' => convertDmyToYmd($request->masa_berlaku_str),

            'nomor_sip' => $request->nomor_sip,

            // DATE
            'masa_berlaku_sip' => convertDmyToYmd($request->masa_berlaku_sip),

            'asuransi_profesi' => $request->asuransi_profesi,
            'nomor_polis' => $request->nomor_polis,

            // DATE
            'masa_berlaku_asuransi' => convertDmyToYmd($request->masa_berlaku_asuransi),

            'pend_diploma' => $request->pend_diploma,
            'pend_s1' => $request->pend_s1,
            'pend_s2' => $request->pend_s2,
            'pend_s3' => $request->pend_s3,
            'kampus_terakhir' => $request->kampus_terakhir,

            'jenjang_pendidikan_terakhir' => $request->jenjang_pendidikan_terakhir,
            'keterangan' => $request->keterangan,

            // DATE
            'tanggal_akhir_kontrak' => convertDmyToYmd($request->tanggal_akhir_kontrak),

            'input_by' => auth()->user()->username ?? null,
            'input_date' => now(),
        ];
    }

    // Generate nomor pekerja
    public function generateNomorPekerjaAjax(Request $request)
    {
        return response()->json([
            'nomor_pekerja' => $this->generateNomorPekerja($request->status_pegawai)
        ]);
    }

    // Buat nomor otomatis
    private function generateNomorPekerja($status = null)
    {
        // Mapping status
        $prefixMap = [
            'Mitra Pegawai' => 'MP',
            'Mitra Dokter' => 'MD',
            'Outsourcing' => 'OS',
            'Internship' => 'INT',
        ];

        // Default
        $prefix = $prefixMap[$status] ?? 'IHC';

        // Ambil nomor terakhir
        $last = DB::table('pegawai')
            ->where('nomor_pekerja', 'like', $prefix . '-%')
            ->orderBy('id', 'desc')
            ->value('nomor_pekerja');

        if (!$last) {
            return $prefix . '00001';
        }

        preg_match('/(\d+)$/', $last, $matches);
        $number = (int) ($matches[1] ?? 0);
        $next = $number + 1;

        return $prefix . '-' . str_pad($next, 5, '0', STR_PAD_LEFT);
    }
}
