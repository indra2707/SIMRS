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

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
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
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }
}
