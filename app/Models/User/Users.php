<?php

namespace App\Models\User;

use App\Models\User\Rolls;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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



    public function rolls()
    {
        return $this->belongsTo(Rolls::class, 'role', 'id');
    }
}
