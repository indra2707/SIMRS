<?php

namespace App\Models\Logistik;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogistikMessage extends Model
{
    use HasFactory;

    protected $table = 'logistik_message';

    protected $fillable = [
        'permintaan_id',
        'user_id',
        'message',
        'sender_type',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke User (pengirim pesan)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Alias untuk user (untuk kompatibilitas)
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Permintaan
     */
    public function permintaan()
    {
        return $this->belongsTo(Permintaan::class, 'permintaan_id');
    }

    /**
     * Scope untuk filter pesan admin
     */
    public function scopeFromAdmin($query)
    {
        return $query->whereHas('user', function($q) {
            $q->whereIn('role', ['admin', 'superadmin', 'support']);
        });
    }

    /**
     * Scope untuk filter pesan user
     */
    public function scopeFromUser($query)
    {
        return $query->whereHas('user', function($q) {
            $q->where('role', 'user');
        });
    }

    /**
     * Accessor untuk cek apakah pengirim adalah admin
     */
    public function getIsAdminAttribute()
    {
        return in_array($this->user->role ?? 'user', ['admin', 'superadmin', 'support']);
    }

    /**
     * Accessor untuk display name
     */
    public function getDisplayNameAttribute()
    {
        return $this->user->nama_lengkap ?? $this->user->username ?? 'Unknown';
    }
}
