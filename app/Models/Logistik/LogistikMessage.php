<?php

namespace App\Models\Logistik;

use App\Models\User;
use App\Models\HelpDesk;
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

     public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

      public function permintaan()
    {
        return $this->belongsTo(Permintaan::class, 'permintaan_id');
    }

   
}
