<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Events\MessageSent;
use App\Models\HelpDesk;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function index($helpdeskId)
    {
        $messages = Message::where('helpdesk_id', $helpdeskId)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json(['messages' => $messages]);
    }

    public function send(Request $request, $helpdeskId)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $user = Auth::user();

        Log::info('Sending message', [
            'user_id' => $user->id,
            'username' => $user->username,
            'role' => $user->role,
            'helpdesk_id' => $helpdeskId
        ]);

        $message = Message::create([
            'helpdesk_id' => $helpdeskId,
            'user_id' => $user->id,
            'sender_type' => $user->role,
            'message' => $request->message,
        ]);

        // Load relasi user
        $message->load('user');

        Log::info('Message created', [
            'message_id' => $message->id,
            'message_data' => $message->toArray()
        ]);

        // Broadcast event
        broadcast(new MessageSent($message))->toOthers();

        // ✅ PENTING: Return data lengkap untuk optimistic update
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $message->id,
                'helpdesk_id' => $message->helpdesk_id,
                'user_id' => $message->user_id,
                'message' => $message->message,
                'sender_type' => $message->sender_type,
                'created_at' => $message->created_at->toISOString(),
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'nama_lengkap' => $user->nama_lengkap ?? $user->name ?? 'Unknown',
                ]
            ]
        ]);
    }
}
