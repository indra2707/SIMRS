<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\Controller;
use App\Models\Logistik\LogistikMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminChatController extends Controller
{
   public function index($permintaanId)
    {
        $messages = LogistikMessage::where('permintaan_id', $permintaanId)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json(['messages' => $messages]);
    }

    public function send(Request $request, $permintaanId)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $user = Auth::user();

        Log::info('Sending message', [
            'user_id' => $user->id,
            'username' => $user->username,
            'role' => $user->role,
            'helpdesk_id' => $permintaanId
        ]);

        $message = LogistikMessage::create([
            'helpdesk_id' => $permintaanId,
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
        // broadcast(new MessageSent($message))->toOthers();

        // ✅ PENTING: Return data lengkap untuk optimistic update
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $message->id,
                'permintaan_id' => $message->permintaan_id,
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
