<?php

namespace App\Models\Sdm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pekerja extends Model
{
    use HasFactory;

    protected $table = 'pekerja';


    protected $guarded = ['id'];
}
