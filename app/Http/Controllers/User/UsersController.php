<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title' => 'User',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'User',
        ];
        return view('user.user.user', $data);
    }

    // Views Table
    public function views()
    {
        // $query = Users::all();
        $query = Db::table('users')
            ->join('tbl_rolls', 'tbl_rolls.id', '=', 'users.role')
            ->select(
                'users.*',
                'tbl_rolls.nama as nama_roll',
            )
            ->get();
        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id' => $value->id,
                'nama_lengkap' => $value->nama_lengkap,
                'username' => $value->username,
                'email' => $value->email,
                'role' => $value->role,
                'nama_roll' => $value->nama_roll,
                'password' => $value->password ? '●●●●●●●●' : '(Belum diset)',
                'status' => $value->status,
            ];
        }
        return response()->json($data, 200);
    }

    // Simpan
    public function store(Request $request)
    {
        $query = Users::create([
            'nama_lengkap'=> $request->nama_lengkap,
            'email'=> $request->email,
            'password'=> bcrypt($request->password),    
            'username'=> $request->username,
            'role'=> $request->role,
            'status' => $request->status == 'on' ? 'aktif' : 'tidak aktif',
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

    // Update
    public function update(Request $request, $id)
    {
        $query = Users::where('id', $id)->update([
            'nama_lengkap'=> $request->nama_lengkap,
            'email'=> $request->email,
            'password'=> bcrypt($request->password),    
            'username'=> $request->username,
            'role'=> $request->role,
            'status' => $request->status == 'on' ? 'aktif' : 'tidak aktif',
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
        $query = Users::where('id', $id)->delete();
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

    // update status check
    public function updateStatus(Request $request, $id)
    {
        $query = Users::where('id', $id)->update([
            'status' => $request->status,
        ]);
        if ($query) {
            return response()->json([
                'success' => true,
                'message' => 'Sukses mengubah status menjadi ' . ($request->status === 'aktif' ? 'aktif' : 'tidak aktif'),
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
}
