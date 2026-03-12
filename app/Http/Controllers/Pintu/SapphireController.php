<?php

namespace App\Http\Controllers\Pintu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pintu\Sapphire;
use Rats\Zkteco\Lib\ZKTeco;
use Exception;


class SapphireController extends Controller
{
    private $ip = "10.128.173.3";
    private $port = 4370;

    public function index()
    {
        $data = [
            'title' => 'sapphire',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'sapphire',
        ];
        return view('pintu.sapphire.sapphire', $data);
    }

    // view
    public function views()
    {
        $query = Sapphire::where('card_number', '!=', '0000000000')
            ->orWhereNull('card_number')
            ->get();

        // $query = sapphire::all();

        $data = [];

        foreach ($query as $value) {
            $data[] = [
                'id' => $value->id,
                'uid' => $value->uid,
                'userid' => $value->userid,
                'name' => $value->name,
                'card_number' => $value->card_number,
                'role' => $value->role,
            ];
        }

        return response()->json($data, 200);
    }


    // // Sinkronisasi dari perangkat
    // public function syncFromDevice()
    // {
    //     try {

    //         $zk = new ZKTeco("10.128.173.3", 4370);

    //         // Jika pakai comm key (cek di mesin)
    //         // $zk->setPassword(0);

    //         if (!$zk->connect()) {
    //             return "Koneksi gagal";
    //         }

    //         $zk->disableDevice();

    //         $device = $zk->getDeviceName();

    //         $zk->enableDevice();
    //         $zk->disconnect();

    //         return $device;

    //     } catch (\Throwable $e) {
    //         return $e->getMessage();
    //     }
    // }



    // Simpan
    public function store(Request $request)
    {
        try {

            $request->validate([
                'userid' => 'required',
                'name' => 'required'
            ]);

            // Generate UID otomatis
            $lastUid = Sapphire::max('uid') ?? 0;
            $newUid = $lastUid + 1;

            // Simpan ke database
            $data = Sapphire::create([
                'uid' => $newUid,
                'userid' => $request->userid,
                'name' => $request->name,
                'card_number' => $request->card_number,
                'role' => $request->role ?? 0
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User berhasil ditambahkan',
                'data' => $data
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Update
    public function update(Request $request, $id)
    {
        try {

            $request->validate([
                'userid' => 'required',
                'name' => 'required'
            ]);

            $user = Sapphire::findOrFail($id);

            // Update database dulu
            $user->update([
                'userid' => $request->userid,
                'name' => $request->name,
                'card_number' => $request->card_number,
                'role' => $request->role ?? 0
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User berhasil diupdate'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // HAPUS USER
    public function destroy($id)
    {
        try {

            $user = Sapphire::findOrFail($id);
            $user->delete();
            return response()->json([
                'status' => true,
                'message' => 'User berhasil dihapus'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
