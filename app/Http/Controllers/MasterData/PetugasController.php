<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MaterData\Petugas;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class PetugasController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title'        => 'Petugas',
            'menuTitle'    => 'Master Data',
            'menuSubtitle' => 'Petugas',
        ];
        return view('master-data.petugas.petugas', $data);
    }

    // Views Table
    public function views()
    {
        $query = Petugas::all();
        $data  = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id'               => $value->id,
                'kode_petugas'     => $value->kode_petugas,
                'nama'             => $value->nama,
                'nik'              => $value->nik,
                'jenis_kelamin'    => $value->jenis_kelamin,
                'status_petugas'   => $value->status_petugas,
                'no_hp'            => $value->no_hp,
                'alamat'           => $value->alamat,
                'kode_bpjs'        => $value->kode_bpjs,
                'kategori'         => $value->kategori,
                'no_sip'           => $value->no_sip,
                'masa_berlaku_sip' => convertYmdToDmy($value->masa_berlaku_sip),
                'kode_spesialis'   => $value->kode_spesialis,
                'tindakan_konsul'  => $value->tindakan_konsul,
                'tindakan_visite'  => $value->tindakan_visite,
                'foto'             => $value->foto,
                'signatures'       => $value->signatures,
                'status'           => $value->status,
            ];
        }
        return Response::json($data, 200);
    }

    // Store
    public function store(Request $request)
    {
        //    make dir images
        if (!is_dir('uploads/images/profil/')) {
            mkdir('uploads/images/profil/', 0777, true);
        }
        $dataValues = [
            'kode_petugas'     => $request->kode_petugas,
            'nama'             => $request->nama,
            'nik'              => str_replace(' ', '', $request->nik),
            'jenis_kelamin'    => $request->jenis_kelamin == 'on' ? 'L' : 'P',
            'status_petugas'   => $request->status_petugas,
            'no_hp'            => str_replace(' ', '', $request->no_hp),
            'alamat'           => $request->alamat,
            'kode_bpjs'        => $request->kode_bpjs,
            'kategori'         => $request->kategori,
            'no_sip'           => $request->no_sip,
            'masa_berlaku_sip' => convertDmyToYmd($request->masa_berlaku_sip),
            'kode_spesialis'   => $request->spesialis,
            'tindakan_konsul'  => $request->tindakan_konsul,
            'tindakan_visite'  => $request->tindakan_visite,
            'status'           => '1',
        ];

        $file = $request->file('profil');
        if ($file != null) {
            $extension = $file->getClientOriginalExtension();
            $filename  = 'Profile_' . strtolower(string: str_replace(' ', '_', $request->nama)) . '_' . time() . '.' . $extension;
            $path      = 'uploads/images/profil/';
            $file->move($path, $filename);
            $dataValues['foto'] = $filename;
        }
        $query = Petugas::create($dataValues);
        if ($query) {
            return response()->json([
                'success' => true,
                'data'    => [],
                'message' => 'Data Berhasil Ditambahkan.',
            ], status: 200);
        } else {
            return response()->json([
                'success' => false,
                'data'    => [],
                'message' => 'Data Gagal Ditambahkan.',
            ], status: 400);
        }
    }

    // Update
    public function update(Request $request, $id)
    {
        $dataValues = [
            'kode_petugas'     => $request->kode_petugas,
            'nama'             => $request->nama,
            'nik'              => str_replace(' ', '', $request->nik),
            'jenis_kelamin'    => $request->jenis_kelamin == 'on' ? 'L' : 'P',
            'status_petugas'   => $request->status_petugas,
            'no_hp'            => str_replace(' ', '', $request->no_hp),
            'alamat'           => $request->alamat,
            'kode_bpjs'        => $request->kode_bpjs,
            'kategori'         => $request->kategori,
            'no_sip'           => $request->no_sip,
            'masa_berlaku_sip' => convertDmyToYmd($request->masa_berlaku_sip),
            'kode_spesialis'   => $request->spesialis,
            'tindakan_konsul'  => $request->tindakan_konsul,
            'tindakan_visite'  => $request->tindakan_visite,
        ];

        $file = $request->file('profil');
        if ($file != null) {
            $extension = $file->getClientOriginalExtension();
            $filename  = 'Profile_' . strtolower(string: str_replace(' ', '_', $request->nama)) . '_' . time() . '.' . $extension;
            $path      = 'uploads/images/profil/';
            $file->move($path, $filename);

            // Hapus Foto Lama
            $data = Petugas::where('id', $id)->first();
            if ($data->foto != null) {
                if (file_exists($path)) {
                    unlink($path . $data->foto);
                }
            }
            $dataValues['foto'] = $filename;
        }
        $query = Petugas::where('id', $id)->update($dataValues);
        if ($query) {
            return response()->json([
                'success' => true,
                'data'    => [],
                'message' => 'Data Berhasil Diubah.',
            ], status: 200);
        } else {
            return response()->json([
                'success' => false,
                'data'    => [],
                'message' => 'Data Gagal Diubah.',
            ], status: 400);
        }
    }
    // Update Signature
    public function update_signature(Request $request, $id)
    {
        if (!is_dir('uploads/images/signature/')) {
            mkdir('uploads/images/signature/', 0777, true);
        }
        $query     = Petugas::where('id', $id)->first();
        if ($query->signatures != null && file_exists('uploads/images/signature/' . $query->signatures)) {
            unlink('uploads/images/signature/' . $query->signatures);
        }
        if ($request->signed != null) {
            $image_parts    = explode(";base64,", $request->signed);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type     = $image_type_aux[1];
            $image_base64   = base64_decode($image_parts[1]);
            $file_name      = 'Signature_' . strtolower(string: str_replace(' ', '_', $request->nama)) . '_' . time() . '.' . $image_type;
            $path           = 'uploads/images/signature/';
            file_put_contents($path . $file_name, $image_base64);
        }
        $dataValues = [
            'signatures' => $file_name
        ];
        $query = Petugas::where('id', $id)->update($dataValues);
        if ($query) {
            return response()->json([
                'success' => true,
                'data'    => [],
                'message' => 'Data Berhasil Diubah.',
            ], status: 200);
        } else {
            return response()->json([
                'success' => false,
                'data'    => [],
                'message' => 'Data Gagal Diubah.',
            ], status: 400);
        }
    }

    // Delete
    public function destroy($id)
    {
        $query = Petugas::where('id', $id)->first();
        if ($query->foto != null && file_exists('uploads/images/profil/' . $query->foto)) {
            unlink('uploads/images/profil/' . $query->foto);
        }

        if ($query->signatures != null && file_exists('uploads/images/signature/' . $query->signatures)) {
            unlink('uploads/images/signature/' . $query->signatures);
        }

        $query = Petugas::where('id', $id)->delete();
        if ($query) {
            return response()->json([
                'success' => true,
                'data'    => [],
                'message' => 'Data Berhasil Dihapus.',
            ], status: 200);
        } else {
            return response()->json([
                'success' => false,
                'data'    => [],
                'message' => 'Data Gagal Dihapus.',
            ], status: 400);
        }
    }
}
