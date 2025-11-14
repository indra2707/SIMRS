<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rolls extends Model
{
    use HasFactory;

    protected $table = 'tbl_rolls';

    protected $fillable = [
        'id',
        'nama',
        'menu',
        'status',
        'created_at',
        'updated_at'
    ];
}
