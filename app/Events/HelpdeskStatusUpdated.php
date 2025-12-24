<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HelpdeskStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $helpdesk;

    public function __construct($helpdesk)
    {
        $this->helpdesk = $helpdesk;
    }

    public function broadcastOn()
    {
        return new Channel('helpdesk-user'); // channel untuk user
    }

    public function broadcastWith()
    {
        return [
           'id' => $this->helpdesk->id,
            'tiket' => $this->helpdesk->tiket, // DIPERBAIKI
            'judul_laporan' => $this->helpdesk->judul_laporan, // DIPERBAIKI
            'status' => $this->helpdesk->status,
            'department' => $this->helpdesk->user->rolls->nama ?? '-',
            'created_at' => $this->helpdesk->created_at->toDateTimeString(),
        ];
    }
}
