<?php

namespace App\Models\Pintu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emerald extends Model
{
    use HasFactory;

    protected $table = 'emerald';

      protected $fillable = [
        'uid',
        'userid',
        'name',
        'card_number',
        'role'
    ];
}
