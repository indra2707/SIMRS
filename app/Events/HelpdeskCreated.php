<?php

namespace App\Events;

use App\Models\HelpDesk;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HelpdeskCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $helpdesk;

    public function __construct(HelpDesk $helpdesk)
    {
        $this->helpdesk = $helpdesk;
    }

    public function broadcastOn()
    {
        return new Channel('helpdesk-admin');
    }

    public function broadcastWith()
    {
        return [
           'id' => $this->helpdesk->id,
            'tiket' => $this->helpdesk->tiket, // DIPERBAIKI
            'judul_laporan' => $this->helpdesk->judul_laporan, // DIPERBAIKI
            'kategori' => $this->helpdesk->kategori, // DIPERBAIKI
            'prioritas' => $this->helpdesk->prioritas, // DIPERBAIKI
            'keterangan' => $this->helpdesk->keterangan,
            'status' => $this->helpdesk->status,
            'tanggal' => $this->helpdesk->tanggal,
            'nama_lengkap' => $this->helpdesk->user->nama_lengkap ?? '-', // DIPERBAIKI
            'department' => $this->helpdesk->user->rolls->nama ?? '-',
            'created_at' => $this->helpdesk->created_at->toDateTimeString(),
        ];
    }
}
