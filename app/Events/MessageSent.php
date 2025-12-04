<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $senderType;

    public function __construct(Message $message, $senderType)
    {
        $this->message = $message->load('user');
        $this->senderType = $senderType;

        // Log untuk debugging
        Log::info('MessageSent Event Created', [
            'message_id' => $message->id,
            'helpdesk_id' => $message->helpdesk_id,
            'sender_type' => $senderType,
            'channel' => 'chat.' . $message->helpdesk_id
        ]);
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn()
    {
        $channel = 'chat.' . $this->message->helpdesk_id;

        Log::info('Broadcasting on channel: ' . $channel);

        // PENTING: Gunakan public Channel, bukan private
        return new Channel($channel);
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs()
    {
        return 'MessageSent';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith()
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'helpdesk_id' => $this->message->helpdesk_id,
                'user_id' => $this->message->user_id,
                'message' => $this->message->message,
                'sender_type' => $this->message->sender_type,
                'created_at' => $this->message->created_at,
                'user' => [
                    'id' => $this->message->user->id,
                    'username' => $this->message->user->username ?? 'Unknown',
                    'nama_lengkap' => $this->message->user->nama_lengkap ?? 'Unknown',
                ]
            ],
            'sender_type' => $this->senderType
        ];
    }
}
