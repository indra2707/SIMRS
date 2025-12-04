<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $table = 'message';
    protected $fillable = [
        'helpdesk_id',
        'user_id',
        'message',
        'sender_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function helpdesk()
    {
        return $this->belongsTo(HelpDesk::class, 'helpdesk_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'helpdesk_id');
    }
}
