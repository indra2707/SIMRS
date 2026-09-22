<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Dompdf\Dompdf;

class AccountController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title' => 'Account',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Account',
        ];
        return view('master-data.account.account', $data);
    }

    // Views
    public function views()
    {
        $pegawai = DB::table('pegawai')
            ->where('id', auth()->user()->id_pegawai)
            ->first();

        if (!$pegawai) {
            return response()->json([
                'message' => 'Data pegawai tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'nik' => substr($pegawai->nik, 0, 6) . '******' . substr($pegawai->nik, -4),
            'nama_pekerja' => $pegawai->nama_pekerja,
            'tanggal_lahir' => Carbon::parse($pegawai->tanggal_lahir)->format('d/m/Y'),
            'email' => $pegawai->email,
            'alamat_domisili' => $pegawai->alamat_domisili,
            'lokasi_kerja' => $pegawai->lokasi_kerja,
            'status_kepegawaian' => $pegawai->status_kepegawaian,
            'nomor_pekerja' => $pegawai->nomor_pekerja,
            'nomor_hp' => $pegawai->nomor_hp,
            'nik2' => $pegawai->nik,
            'status_pernikahan' => $pegawai->status_pernikahan,
            'agama' => $pegawai->agama,
            'golongan_darah' => $pegawai->golongan_darah,
            'nomor_kontak_darurat' => $pegawai->nomor_kontak_darurat,
            'nama_kontak_darurat' => $pegawai->nama_kontak_darurat,
            'hubungan_kontak_darurat' => $pegawai->hubungan_kontak_darurat,
            'id' => $pegawai->id,
            'foto' => $pegawai->foto,
            'id_bank' => $pegawai->id_bank,
            'nomor_rekening' => $pegawai->nomor_rekening,
            'nama_rekening' => $pegawai->nama_rekening
        ], 200);
    }

    // Update
    public function update(Request $request, $id)
    {
        // Validasi
        $request->validate([
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'foto.image' => 'File yang diupload harus berupa gambar.',
            'foto.mimes' => 'Format foto hanya boleh JPG, JPEG, atau PNG.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        // Ambil data pegawai
        $pegawai = DB::table('pegawai')
            ->where('id', $id)
            ->first();

        if (!$pegawai) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Data pegawai tidak ditemukan.',
            ], 404);
        }

        // Data update
        $data = [
            'nomor_hp' => $request->nomor_hp,
            'nomor_kontak_darurat' => $request->nomor_kontak_darurat,
            'nama_kontak_darurat' => $request->nama_kontak_darurat,
            'hubungan_kontak_darurat' => $request->hubungan_kontak_darurat,
            'alamat_domisili' => $request->alamat_domisili,
            'email' => $request->email,
            'nik' => $request->nik2,
            'status_pernikahan' => $request->status_pernikahan,
            'agama' => $request->agama,
            'golongan_darah' => $request->golongan_darah,
            'tanggal_lahir' => Carbon::createFromFormat('d/m/Y', $request->tanggal_lahir)->format('Y-m-d'),
            'id_bank' => $request->id_bank,
            'nomor_rekening' => $request->nomor_rekening,
            'nama_rekening' => $request->nama_rekening,
        ];

        // Upload foto
        if ($request->hasFile('foto')) {

            $file = $request->file('foto');

            // Folder
            $folder = public_path('uploads/images/foto-pegawai');

            // Buat folder jika belum ada
            if (!file_exists($folder)) {
                mkdir($folder, 0755, true);
            }

            // Hapus foto lama
            if (!empty($pegawai->foto)) {

                $fotoLama = $folder . '/' . $pegawai->foto;

                if (file_exists($fotoLama)) {
                    unlink($fotoLama);
                }
            }

            // Nama file
            $namaFile = 'pegawai_' . $id . '_' . time() . '.' .
                $file->getClientOriginalExtension();

            // Simpan file
            $file->move($folder, $namaFile);

            // Simpan nama file ke database
            $data['foto'] = $namaFile;
        }

        // Update database
        $query = DB::table('pegawai')
            ->where('id', $id)
            ->update($data);

        if ($query) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Data Berhasil Diubah.',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'data' => [],
            'message' => 'Data Gagal Diubah.',
        ], 400);
    }


    // Print PDF
    public function printPdf($id)
    {
        // Ambil data pegawai
        $pegawai = DB::table('pegawai')
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
            ->where('pegawai.id', $id)
            ->first();

        // Validasi data
        if (!$pegawai) {
            abort(404, 'Data pegawai tidak ditemukan.');
        }

        // Ambil seluruh data ijazah berdasarkan ID pegawai
        $ijazah = DB::table('tbl_ijazah')->where('id_pegawai', $id)->orderByDesc('tahun_lulus')->get();
        $skjabatan = DB::table('tbl_sk_jabatan')->where('id_pegawai', $id)->orderByDesc('id')->get();
        $kontrak = DB::table('tbl_kontrak')->where('id_pegawai', $id)->orderByDesc('id')->get();
        $sertifikat = DB::table('tbl_sertifikat')->where('id_pegawai', $id)->orderByDesc('id')->get();
        $str = DB::table('tbl_str_sip')->where('id_pegawai', $id)->orderByDesc('id')->get();
        $spk = DB::table('tbl_spk_rkk')->where('id_pegawai', $id)->orderByDesc('id')->get();
        
        // Render Blade menjadi HTML
        $html = view('master-data.account.print', [
        'pegawai' => $pegawai, 
        'ijazah' => $ijazah, 
        'skjabatan' => $skjabatan, 
        'kontrak' => $kontrak,
        'sertifikat' => $sertifikat,
        'str' => $str,
        'spk' => $spk,
        ])->render();

        // Buat PDF menggunakan Dompdf
        $dompdf = new Dompdf();

        $dompdf->loadHtml($html);

        // Ukuran kertas
        $dompdf->setPaper('A4', 'portrait');

        // Render PDF
        $dompdf->render();

        // Nama file
        $namaFile = 'Account_' . $pegawai->id . '.pdf';

        // Tampilkan PDF di browser
        return $dompdf->stream($namaFile, [
            'Attachment' => false
        ]);
    }

}
