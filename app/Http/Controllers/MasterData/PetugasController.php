<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MaterData\Petugas;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class PetugasController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title' => 'Petugas',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Petugas',
        ];
        return view('master-data.petugas.petugas', $data);
    }

    // Views Table
    public function views()
    {
        $query = Petugas::all();

        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id' => $value->id,
                'kode' => $value->kode,
                'kategori' => $value->kategori,
                'nama' => $value->nama,
                'status' => $value->status,
            ];
        }
        return response()->json($data, 200);
    }

    // Select Spesialis
    public function select()
    {
        $query = DB::table('Petugas')
            ->where('status', '1')
            ->where('kategori', 'penjamin')
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $data[$key]['id']   = $value->id;
            $data[$key]['text'] = $value->kode . ' - ' . $value->nama;
        }
        return response()->json([
            'data' => $data
        ], 200);
    }

    // Store
    public function store(Request $request)
    {
        //    make dir images
        if (!is_dir('uploads/images/profil/')) {
            mkdir('uploads/images/profil/', 0777, true);
        }
        $filename = NULL;
        $path = NULL;
        if ($request->has('profil')) {
            $file = $request->file('profil');
            $extension = $file->getClientOriginalExtension();
            $filename = 'Profile' . strtolower (string: str_replace(' ', '_', $request->nama)) . '_' . time() . '.' . $extension;
            $path = 'uploads/images/profil/';
            $file->move($path, $filename);
        }

        //    make dir images
        // if (!is_dir('uploads/images/signatur/')) {
        //     mkdir('uploads/images/signatur/', 0777, true);
        // }
        
        // $image_parts = explode(";base64,", $request->signed);
        // $image_type_aux = explode("image/", $image_parts[0]);
        // $image_type = $image_type_aux[1];      
        // $image_base64 = base64_decode($image_parts[1]);
        // $file_name = 'uploads/images/signatur/' . 'Signature_' . strtolower (string: str_replace(' ', '_', $request->nama)) . '_' . time() . '.'.$image_type;
        // file_put_contents($file_name, $image_base64);

        $query = Petugas::create([
            'id' => $request->id,
            'kategori' => $request->kategori,
            'kode' => $request->kode,
            'nik' => $request->nik,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin == 'on' ? 'L' : 'P',
            'no_hp' => $request->no_hp,
            'kode_spesialis' => $request->kode_spesialis,
            'kode_bpjs' => $request->kode_bpjs,
            'alamat' => $request->alamat,
            'kode_tindakan1' => $request->kode_tindakan1,
            'kode_tindakan2' => $request->kode_tindakan2,
            'tanggal' => $request->tanggal,
            'foto' => $filename,
            'ttd' =>str_replace('uploads/images/signatur/','',$file_name),
            'status_dokter' => $request->status_dokter,
            'status' => $request->status == 'on' ? '1' : '0',
        ]);
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

    // update status check
    public function updateStatus(Request $request, $id)
    {
        $query = Petugas::where('id', $id)->update([
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

    // Update
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required',
            'nama' => 'required',
            'kategori' => 'required',
        ]);
        $query = Petugas::where('id', $id)->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'status' => $request->status == 'on' ? '1' : '0',
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

    // Delete
    public function destroy($id)
    {
        $query = Petugas::where('id', $id)->delete();
        if ($query) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Data Berhasil Dihapus.',
            ], status: 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Data Gagal Dihapus.',
            ], status: 400);
        }
    }
}
