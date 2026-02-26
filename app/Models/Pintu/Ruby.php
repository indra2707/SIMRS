<?php

namespace App\Models\Pintu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruby extends Model
{
    use HasFactory;

     protected $table = 'ruby';

      protected $fillable = [
        'uid',
        'userid',
        'name',
        'card_number',
        'role'
    ];
}
