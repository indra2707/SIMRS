<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
            'foto' => $pegawai->foto
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
            'tanggal_lahir' => Carbon::createFromFormat(
                'd/m/Y',
                $request->tanggal_lahir
            )->format('Y-m-d'),
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
}
