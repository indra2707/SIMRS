<?php

namespace App\Http\Controllers\Pintu;

use App\Http\Controllers\Controller;
use App\Models\Pintu\Ruby;
use Illuminate\Http\Request;
use Rats\Zkteco\Lib\ZKTeco;


class RubyController extends Controller
{
    private $ip = "10.128.173.5";
    private $port = 4370;

    public function index()
    {
        $data = [
            'title' => 'Ruby',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Ruby',
        ];
        return view('pintu.ruby.ruby', $data);
    }

    // view
    public function views()
    {
        $query = Ruby::where('card_number', '!=', '0000000000')
            ->orWhereNull('card_number')
            ->get();

        // $query = Ruby::all();

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
            Ruby::updateOrCreate(
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
                'name' => 'required',
                'card_number' => 'nullable|numeric'
            ]);

            // Generate UID otomatis
            $lastUid = Ruby::max('uid') ?? 0;
            $newUid = (int) $lastUid + 1;

            // Format data sebelum disimpan
            $userid = trim($request->userid);
            $name = substr(trim($request->name), 0, 24); // max 24 karakter
            $role = (int) ($request->role ?? 0);
            $card = $request->card_number ? (int) $request->card_number : 0;

            // Simpan ke database
            $data = Ruby::create([
                'uid' => $newUid,
                'userid' => $userid,
                'name' => $name,
                'card_number' => $card,
                'role' => $role
            ]);

            $zk = new ZKTeco($this->ip, $this->port);

            if (!$zk->connect()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal konek ke mesin'
                ], 500);
            }

            $zk->disableDevice();

            // Kirim user ke mesin
            $zk->setUser(
                (int) $newUid,   // UID
                (string) $userid,// UserID
                $name,          // Nama
                '',             // Password
                $role,          // Role
                $card           // Card Number harus INT
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

            $user = Ruby::findOrFail($id);

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

            $user = Ruby::findOrFail($id);

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


    //Open Pintu
    public function openDoor()
    {
        $zk = new ZKTeco($this->ip, $this->port);

        try {

            if (!$zk->connect()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal koneksi ke device'
                ], 500);
            }

            $zk->disableDevice();
            // $zk->openDoor();
            sleep(5);
            $zk->enableDevice();
            $zk->disconnect();

            return response()->json([
                'status' => 'success',
                'message' => 'Pintu berhasil dibuka'
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
