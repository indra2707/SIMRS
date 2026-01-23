<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class UserspekerjaController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title' => 'User',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'User',
        ];
        return view('user.user-perkerja.user-pekerja', $data);
    }

    // Views Table
    public function views()
    {
        // $query = Users::all();
        $query = Db::table('users')
            ->join('tbl_rolls', 'tbl_rolls.id', '=', 'users.role')
            ->join('pegawai', 'pegawai.id', '=', 'users.id_pegawai')
            ->where('users.id', session('id'))
            ->select(
                'users.*',
                'tbl_rolls.nama as nama_roll',
                'pegawai.nama_pekerja as nama_pekerja'
            )
            ->get();
        $data = [];
        foreach ($query as $key => $value) {
            $data[] = [
                'id' => $value->id,
                'id_pegawai' => $value->id_pegawai,
                'nama_pekerja' => $value->nama_pekerja,
                'username' => $value->username,
                'role' => $value->role,
                'nama_roll' => $value->nama_roll,
                'password' => $value->password ? '●●●●●●●●' : '(Belum diset)',
                'status' => $value->status,
            ];
        }
        return response()->json($data, 200);
    }
}
