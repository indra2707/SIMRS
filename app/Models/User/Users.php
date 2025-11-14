<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'id',
        'role',
        'username',
        'nama_lengkap',
        'email_verified_at',
        'password',
        'status',
        'remember_token',
        'email',
        'created_at',
        'updated_at'
    ];
}
