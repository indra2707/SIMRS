<?php

namespace App\Http\Controllers\Pintu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pintu\Sapphire;
use Rats\Zkteco\Lib\ZKTeco;

class SapphireController extends Controller
{
    private $ip = "10.128.173.14";
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


    // Sinkronisasi dari perangkat
    public function syncFromDevice()
    {
        $zk = new ZKTeco($this->ip, $this->port); // pakai port

        if (!$zk->connect()) {
            return response()->json([
                'status' => false,
                'message' => 'Tidak bisa konek ke mesin'
            ], 500);
        }

        $zk->disableDevice();
        $deviceUsers = $zk->getUser();
        $zk->enableDevice();
        $zk->disconnect();

        if (!$deviceUsers) {
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada data user dari mesin'
            ], 404);
        }

        $total = 0;

        foreach ($deviceUsers as $user) {
            Sapphire::updateOrCreate(
                ['userid' => $user['userid']],
                [
                    'uid' => $user['uid'],
                    'name' => $user['name'],
                    'card_number' => $user['cardno'] ?? null,
                    'role' => $user['role']
                ]
            );

            $total++;
        }

        return response()->json([
            'status' => true,
            'message' => 'Berhasil sinkron user dari mesin',
            'total_user' => $total
        ]);
    }

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

            $zk = new ZKTeco($this->ip, $this->port);

            if (!$zk->connect()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal konek ke mesin'
                ], 500);
            }

            $zk->disableDevice();

            // Format parameter yang benar
            $zk->setUser(
                $newUid,                    // UID (HARUS INT)
                $request->userid,           // UserID
                $request->name,             // Nama
                '',                         // Password
                $request->role ?? 0,        // Role
                $request->card_number ?? '' // Card
            );

            $zk->enableDevice();
            $zk->disconnect();

            return response()->json([
                'status' => true,
                'message' => 'User berhasil ditambahkan ke mesin dan database',
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

            $zk = new ZKTeco($this->ip, $this->port);

            if (!$zk->connect()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal konek ke mesin'
                ], 500);
            }

            $zk->disableDevice();

            // 🔥 REMOVE DULU (lebih aman)
            $zk->removeUser($user->uid);

            // 🔥 SET ULANG
            $zk->setUser(
                (int) $user->uid,
                (string) $user->userid,
                (string) $user->name,
                '',
                (int) ($user->role ?? 0),
                (string) ($user->card_number ?? '')
            );

            $zk->enableDevice();
            $zk->disconnect();

            return response()->json([
                'status' => true,
                'message' => 'User berhasil diupdate di mesin dan database'
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

            $zk = new ZKTeco($this->ip, $this->port);

            if (!$zk->connect()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal konek ke mesin'
                ], 500);
            }

            $zk->disableDevice();

            // HAPUS BERDASARKAN UID
            $zk->removeUser($user->uid);

            $zk->enableDevice();
            $zk->disconnect();

            $user->delete();

            return response()->json([
                'status' => true,
                'message' => 'User berhasil dihapus dari mesin dan database'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
