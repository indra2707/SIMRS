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
            'nomor_kontak_darurat' => $pegawai->nomor_kontak_darurat,
            'nama_kontak_darurat' => $pegawai->nama_kontak_darurat,
            'hubungan_kontak_darurat' => $pegawai->hubungan_kontak_darurat,
            'id' => $pegawai->id,
        ], 200);
    }

    // Update
    public function update(Request $request, $id)
    {
        $query = DB::table('pegawai')
            ->where('id', $id)->update([
                    'nomor_hp' => $request->nomor_hp,
                    'nomor_kontak_darurat' => $request->nomor_kontak_darurat,
                    'nama_kontak_darurat' => $request->nama_kontak_darurat,
                    'hubungan_kontak_darurat' => $request->hubungan_kontak_darurat,
                    'alamat_domisili' => $request->alamat_domisili,
                    'email' => $request->email,
                    'tanggal_lahir' => Carbon::createFromFormat('d/m/Y', $request->tanggal_lahir)->format('Y-m-d'),
                ]);
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
}
