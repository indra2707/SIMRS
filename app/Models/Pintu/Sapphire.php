<?php

namespace App\Models\Pintu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sapphire extends Model
{
    use HasFactory;
     protected $table = 'sapphire';

      protected $fillable = [
        'uid',
        'userid',
        'name',
        'card_number',
        'role'
    ];
}
