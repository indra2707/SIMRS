<?php

namespace App\Models\MaterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'tbl_vendors';

    protected $fillable = [
        'id',
        'nama',
        'status',
        'created_at',
        'updated_at'
    ];
}
