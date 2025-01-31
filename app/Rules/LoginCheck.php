<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginCheck implements ValidationRule
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $username = $this->request->input('username');
        $password = $this->request->input('password');
        $remember = $this->request->input('remember');
        $usernameCheck = User::where('username', $username);
        if ($usernameCheck->exists()) {
            if (Auth::attempt(['username' => $username, 'password' => $password])) {
                if ($usernameCheck->first()->status == 'aktif') {
                    if (isset($remember)) {
                        setcookie('username', $username, time() + 7200); // 7200 = 2 jam
                        setcookie('password', $password, time() + 7200); // 7200 = 2 jam
                    } else {
                        setcookie('username', "");
                        setcookie('password', "");
                    }
                    $adminData = User::where('username', $username)->first();
                    Session::put('LoginStatus', value: true);
                    Session::put('role', $adminData->role);
                    Session::put('username', $adminData->username);
                    Session::put('fullname', $adminData->name);
                    Session::put('email', $adminData->email);
                } else {
                    $fail('User sudah tidak aktif, silahkan hubungi Admin untuk mengaktifkan akun.');
                }
            } else {
                $fail('Password yang anda masukan Salah.');
            }
        } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Username yang anda masukan tidak terdaftar.'
                ], 400);
            // $fail('Username yang anda masukan tidak terdaftar.');
        }
    }
}
