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

    public function getUnreadCountForUser($userId)
    {
        $user = \App\Models\User::find($userId);

        if (!$user) return 0;

        // Admin/Staff: hitung pesan dari user (role='user') yang belum dibaca
        if ($user->role !== 'user') {
            return $this->messages()
                ->unread()
                ->whereHas('user', function ($q) {
                    $q->where('role', 'user');
                })
                ->count();
        }

        // User biasa: hitung pesan dari admin/staff yang belum dibaca
        return $this->messages()
            ->unread()
            ->whereHas('user', function ($q) {
                $q->where('role', '!=', 'user');
            })
            ->count();
    }

    /**
     * Accessor untuk unread count (berdasarkan user yang login)
     */
    public function getUnreadCountAttribute()
    {
        if (!auth()->check()) return 0;

        return $this->getUnreadCountForUser(auth()->id());
    }
}
