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
            'keterangan' => $this->helpdesk->keterangan,
            'nama_lengkap' => $this->helpdesk->user->nama_lengkap,
            'department' => $this->helpdesk->user->department->nama ?? '',
            'tanggal' => $this->helpdesk->tanggal,
            'created_at' => $this->helpdesk->created_at->toDateTimeString(),
            'status' => $this->helpdesk->status,
        ];
    }
}
