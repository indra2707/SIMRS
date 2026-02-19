<?php

namespace App\Http\Controllers\Sdm;

use App\Models\Sdm\Pegawai;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PegawaiImport;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;


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
        try {
            // Debug: Cek total data di pegawai
            $totalPegawai = DB::table('pegawai')->count();
            Log::info("Total pegawai di database: {$totalPegawai}");

            $query = DB::table('pegawai')
                ->leftJoin('tbl_sk_struktur', 'tbl_sk_struktur.id', '=', 'pegawai.id_sk_struktur')
                ->leftJoin('tbl_jabatan', 'tbl_jabatan.id', '=', 'pegawai.id_jabatan')
                ->leftJoin('tbl_fungsi', 'tbl_fungsi.id', '=', 'pegawai.id_sub_fungsi')
                ->leftJoin('tbl_bank', 'tbl_bank.id', '=', 'pegawai.id_bank')
                ->leftJoin('tbl_unit', 'tbl_unit.id', '=', 'pegawai.id_unit')
                ->select(
                    'pegawai.*',
                    'tbl_sk_struktur.no_sk as no_sk_struktur',
                    'tbl_jabatan.nama_jabatan as nama_jabatan',
                    'tbl_jabatan.unit as nama_rumah_sakit',
                    'tbl_fungsi.nama_fungsi as nama_fungsi',
                    'tbl_bank.nama_bank as nama_bank',
                    'tbl_unit.nama as nama_unit'
                )
                ->orderBy('pegawai.id', 'desc')
                ->get();

            Log::info("Data hasil LEFT JOIN: " . $query->count());

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
                    'no_sk_struktur' => $value->no_sk_struktur ?? '-',
                    'jabatan' => $value->jabatan ?? null,
                    'nama_jabatan' => $value->nama_jabatan ?? '-',
                    'penempatan' => $value->penempatan ?? null,
                    'lokasi_kerja' => $value->lokasi_kerja ?? null,
                    'nama_rumah_sakit' => $value->nama_rumah_sakit ?? '-',

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
                    'id_unit' => $value->id_unit ?? null,
                    'nama_fungsi' => $value->nama_fungsi ?? '-',
                    'tmt_jabatan' => $value->tmt_jabatan ?? null,
                    'tmt_jabatan_formatted' => $value->tmt_jabatan ? Carbon::parse($value->tmt_jabatan)->format('d M Y') : null,
                    'tmt_golongan_upah' => $value->tmt_golongan_upah ?? null,
                    'tmt_golongan_upah_formatted' => $value->tmt_golongan_upah ? Carbon::parse($value->tmt_golongan_upah)->format('d M Y') : null,
                    'penyetaraan_jabatan_ap' => $value->penyetaraan_jabatan_ap ?? null,
                    'penyetaraan_golongan_upah_ap' => $value->penyetaraan_golongan_upah_ap ?? null,

                    // Banking Info
                    'nama_bank' => $value->nama_bank ?? '-',
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
                    'status' => $value->status ?? null,
                ];
            }

            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error di views(): ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            // Fallback: Return data tanpa JOIN jika error
            $simplQuery = DB::table('pegawai')
                ->select('*')
                ->orderBy('id', 'desc')
                ->get();

            $data = [];
            foreach ($simplQuery as $value) {
                $data[] = [
                    'id' => $value->id,
                    'nomor_pekerja' => $value->nomor_pekerja,
                    'nama_pekerja' => $value->nama_pekerja,
                    'jenis_kelamin' => $value->jenis_kelamin,
                    'tanggal_lahir' => $value->tanggal_lahir,
                    'tanggal_lahir_formatted' => $value->tanggal_lahir ? Carbon::parse($value->tanggal_lahir)->format('d M Y') : null,
                    'status_kepegawaian' => $value->status_kepegawaian,
                    'nomor_hp' => $value->nomor_hp,
                    'email' => $value->email,
                    'no_sk_struktur' => '-',
                    'nama_jabatan' => '-',
                    'nama_fungsi' => '-',
                    'nama_bank' => '-',
                ];
            }

            return response()->json($data);
        }
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
            'id_unit' => $request->id_unit,
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
            'id_unit' => $request->id_unit,
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
            'Internship' => 'MG',
        ];

        $prefix = $prefixMap[$status] ?? 'IHC';

        // Ambil nomor pekerja TERAKHIR berdasarkan angka
        $last = DB::table('pegawai')
            ->where('nomor_pekerja', 'like', $prefix . '%')
            ->orderByRaw(
                "CAST(SUBSTRING(nomor_pekerja, " . (strlen($prefix) + 1) . ") AS UNSIGNED) DESC"
            )
            ->value('nomor_pekerja');

        // Jika belum ada data
        if (!$last) {
            return $prefix . '00001';
        }

        // Ambil angka terakhir
        $number = (int) substr($last, strlen($prefix));
        $next = $number + 1;

        return $prefix . str_pad($next, 5, '0', STR_PAD_LEFT);
    }


    // update status check
    public function updateStatus(Request $request, $id)
    {
        $query = Pegawai::where('id', $id)->update([
            'status' => $request->status,
        ]);
        if ($query) {
            return response()->json([
                'success' => true,
                'message' => 'Sukses mengubah status menjadi ' . ($request->status === '1' ? 'Aktif' : 'Tidak Aktif'),
                'data' => [],
            ], status: 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status.',
                'data' => [],
            ], status: 400);
        }
    }
    public function import(Request $request)
    {
        $uploadPath = 'assets/file_pegawai/';
        $fullPath = public_path($uploadPath);

        if (!is_dir($fullPath)) {
            mkdir($fullPath, 0777, true);
        }

        $this->validate($request, [
            'file' => 'required|mimes:xls,xlsx,csv'
        ]);

        $file = $request->file('file');
        $nama_file = time() . '_' . $file->getClientOriginalName();

        $file->move($fullPath, $nama_file);

        $filePath = $fullPath . $nama_file;

        if (!file_exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'File gagal di-upload',
            ], 500);
        }

        try {
            Excel::import(new PegawaiImport, $filePath);

            @unlink($filePath);

            return response()->json([
                'success' => true,
                'message' => 'Data Pegawai Berhasil Diimport.',
            ], 200);
        } catch (\Exception $e) {
            @unlink($filePath);

            return response()->json([
                'success' => false,
                'message' => 'Gagal import data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function downloadTemplate()
    {
        try {
            // Header template
            $headers = [
                'anak_perusahaan',
                'id_sk_struktur',
                'id_jabatan',
                'penempatan',
                'lokasi_kerja',
                'status_kepegawaian',
                'nomor_pekerja',
                'nama_pekerja',
                'jenis_kelamin',
                'agama',
                'nik',
                'status_pernikahan',
                'golongan_darah',
                'disabilitas',
                'tanggal_lahir',
                'golongan_upah',
                'tmt_status_kepegawaian',
                'tmt_pwtt',
                'tmt_pwt',
                'masa_kerja',
                'fungsi',
                'id_sub_fungsi',
                'tmt_jabatan',
                'tmt_golongan_upah',
                'penyetaraan_jabatan_ap',
                'penyetaraan_golongan_upah_ap',
                'id_bank',
                'nomor_rekening',
                'nama_rekening',
                'nomor_bpjstk',
                'nomor_bpjskesehatan',
                'nomor_npwp',
                'nomor_hp',
                'nomor_kontak_darurat',
                'nama_kontak_darurat',
                'hubungan_kontak_darurat',
                'email',
                'email_dinas',
                'alamat_ktp',
                'alamat_npwp',
                'alamat_domisili',
                'nomor_str',
                'str_seumur_hidup',
                'masa_berlaku_str',
                'nomor_sip',
                'masa_berlaku_sip',
                'asuransi_profesi',
                'nomor_polis',
                'masa_berlaku_asuransi',
                'pend_diploma',
                'pend_s1',
                'pend_s2',
                'pend_s3',
                'kampus_terakhir',
                'jenjang_pendidikan_terakhir',
                'keterangan',
                'tanggal_akhir_kontrak'
            ];

            // Contoh data
            $example = [
                'PT Pertamina Bina Medika IHC',  // anak_perusahaan
                '3',                              // id_sk_struktur
                '76',                             // id_jabatan
                'RS Pertamina Royal Biringkanaya', // penempatan
                'RS Pertamina Royal Biringkanaya', // lokasi_kerja
                'PWTT',                           // status_kepegawaian
                '59061803',                       // nomor_pekerja
                'Dr. John Doe',                   // nama_pekerja
                'Laki-laki',                      // jenis_kelamin
                'Islam',                          // agama
                '3174010101900001',               // nik
                'Menikah',                        // status_pernikahan
                'A',                              // golongan_darah
                'Tidak',                          // disabilitas
                '1990-01-01',                     // tanggal_lahir (WAJIB)
                'Biasa',                          // golongan_upah
                '',                               // tmt_status_kepegawaian
                '',                               // tmt_pwtt
                '',                               // tmt_pwt
                '3 Tahun',                        // masa_kerja
                'Non Medis',                      // fungsi
                '22',                             // id_sub_fungsi
                '',                               // tmt_jabatan
                '',                               // tmt_golongan_upah
                'Grade 5',                        // penyetaraan_jabatan_ap
                'Grade 5',                        // penyetaraan_golongan_upah_ap
                '1',                              // id_bank
                '1020304050',                     // nomor_rekening
                'Dr. John Doe',                   // nama_rekening
                '10030602725',                    // nomor_bpjstk
                '1639591165',                     // nomor_bpjskesehatan
                '952762000000013',                // nomor_npwp
                '081234567890',                   // nomor_hp
                '081987654321',                   // nomor_kontak_darurat
                'Jane Doe',                       // nama_kontak_darurat
                'Istri',                          // hubungan_kontak_darurat
                'johndoe@email.com',              // email
                'johndoe@ihc.id',                 // email_dinas
                'Jl. Merdeka No. 123, Jakarta',  // alamat_ktp
                'Jl. Merdeka No. 123, Jakarta',  // alamat_npwp
                'Jl. Merdeka No. 123, Jakarta',  // alamat_domisili
                'STR123456',                      // nomor_str
                'Ya',                             // str_seumur_hidup
                '',                               // masa_berlaku_str
                'SIP123456',                      // nomor_sip
                '2025-12-31',                     // masa_berlaku_sip
                'Asuransi A',                     // asuransi_profesi
                'POL123456',                      // nomor_polis
                '2025-12-31',                     // masa_berlaku_asuransi
                '',                               // pend_diploma
                'S1 Kedokteran Umum',             // pend_s1
                'S2 Spesialis Penyakit Dalam',   // pend_s2
                '',                               // pend_s3
                'Universitas Indonesia',          // kampus_terakhir
                'S2',                             // jenjang_pendidikan_terakhir
                'Aktif',                          // keterangan
                ''                                // tanggal_akhir_kontrak
            ];

            // Generate Excel menggunakan class anonymous
            return Excel::download(new class($headers, $example) implements
                \Maatwebsite\Excel\Concerns\FromArray,
                \Maatwebsite\Excel\Concerns\WithHeadings,
                \Maatwebsite\Excel\Concerns\WithStyles,
                \Maatwebsite\Excel\Concerns\ShouldAutoSize
            {
                protected $headers;
                protected $example;

                public function __construct($headers, $example)
                {
                    $this->headers = $headers;
                    $this->example = $example;
                }

                public function array(): array
                {
                    return [$this->example]; // Data contoh
                }

                public function headings(): array
                {
                    return [$this->headers]; // Header
                }

                public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
                {
                    return [
                        1 => [
                            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => ['rgb' => '4472C4']
                            ]
                        ],
                    ];
                }
            }, 'Template_Import_Pegawai.xlsx');
        } catch (\Exception $e) {
            Log::error('Download template error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal download template: ' . $e->getMessage(),
            ], 500);
        }
    }
}
