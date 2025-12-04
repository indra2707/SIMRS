<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpDesk extends Model
{
    use HasFactory;

    protected $table = 'help_desk';

    protected $fillable = [
        'user_id',
        'keterangan',
        'status',
        'tanggal',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function messages()
    {
        return $this->hasMany(Message::class, 'helpdesk_id');
    }
}
