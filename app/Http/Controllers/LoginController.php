<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\LoginCheck;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    // Index
    public function index()
    {
        return view('authentication.login');
    }

    public function login(Request $request)
    {
        if (Session::get('LoginStatus')) {
            return redirect()->route('home');
        } else {
            $usernameCheck = User::where('username', $request->username);
            if ($usernameCheck->exists()) {
                if (Auth::attempt(['username' => $request->username, 'password' => $request->password], $request->remember)) {
                    if ($usernameCheck->first()->status == 'aktif') {
                        if ($request->remember == 'true') {
                            setcookie('username', $request->username, time() + 7200); // 7200 = 2 jam
                            setcookie('password', $request->password, time() + 7200); // 7200 = 2 jam
                        } else {
                            setcookie('username', "");
                            setcookie('password', "");
                        }
                        $adminData = User::where('username', $request->username)->first();
                        Session::put('LoginStatus', value: true);
                        Session::put('role', $adminData->role);
                        Session::put('username', $adminData->username);
                        Session::put('fullname', $adminData->name);
                        Session::put('email', $adminData->email);
                        return response()->json([
                            'success' => true,
                            'data' => [],
                            'message' => 'Login Berhasil'
                        ], status: 200);
                    } else {
                        return response()->json([
                            'success' => false,
                            'data' => [],
                            'message' => 'User sudah tidak aktif, silahkan hubungi Admin untuk mengaktifkan akun.'
                        ], 400);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'data' => [],
                        'message' => 'Password yang anda masukan Salah.'
                    ], 400);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'data' => [],
                    'message' => 'Username yang anda masukan tidak terdaftar.'
                ], 400);
            }
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        Session::flush();
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
