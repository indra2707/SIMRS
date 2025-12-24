<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Message;
use App\Models\User\Users;


class HelpDesk extends Model
{
    use HasFactory;

    protected $table = 'help_desk';

    protected $fillable = [
        'user_id',
        'tiket',
        'judul_laporan',
        'kategori',
        'prioritas',
        'keterangan',
        'status',
        'tanggal',
        'gambar',
    ];

     protected $casts = [
        'gambar' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }
    public function messages()
    {
        return $this->hasMany(Message::class, 'helpdesk_id');
    }

    // public function lampirans()
    // {
    //     return $this->hasMany(HelpDeskLampiran::class, 'helpdesk_id');
    // }

    public function getUnreadCountForUser($userId)
    {
        $user = \App\Models\User::find($userId);

        if (!$user)
            return 0;

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
        if (!auth()->check())
            return 0;

        return $this->getUnreadCountForUser(auth()->id());
    }

}
