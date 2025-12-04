<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Events\MessageSent;
use App\Models\HelpDesk;

class ChatController extends Controller
{
    public function index($helpdeskId)
    {
        $messages = Message::where('helpdesk_id', $helpdeskId)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json(['messages' => $messages]);
    }

    public function send(Request $request, $helpdeskId)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        // Deteksi guard aktif (admin / user)
        $guard = auth('admin')->check() ? 'admin' : 'user';
        $sender = auth($guard)->user();

        $message = Message::create([
            'helpdesk_id' => $helpdeskId,
            'sender_id' => $sender->id,
            'sender_type' => get_class($sender),
            'message' => $request->message,
        ]);

        // broadcast(new MessageSent($message))->toOthers();

        return response()->json(['success' => true]);
    }
}
