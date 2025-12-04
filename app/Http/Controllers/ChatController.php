<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\HelpDesk;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function index($helpdesk_id)
    {
        try {
            $messages = Message::with('user')
                ->where('helpdesk_id', $helpdesk_id)
                ->orderBy('created_at', 'asc')
                ->get();

            return response()->json($messages);
        } catch (\Exception $e) {
            Log::error('Error loading messages: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat pesan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function send(Request $request, $helpdesk_id)
    {
        try {
            // Validasi input
            $request->validate([
                'message' => 'required|string|max:5000'
            ]);

            // Cek guard mana yang sedang login
            // $guard = Auth::guard('admin')->check() ? 'admin' : 'user';
            // $user = Auth::guard($guard)->user();
            $user = Auth::user();
            $role = $user->role;

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak terautentikasi'
                ], 401);
            }

            // Cek apakah helpdesk exists
            $helpdesk = HelpDesk::find($helpdesk_id);
            if (!$helpdesk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Helpdesk tidak ditemukan'
                ], 404);
            }

            // Create message
            $message = Message::create([
                'helpdesk_id' => $helpdesk_id,
                'user_id' => $user->id,
                'message' => $request->message,
                'sender_type' => $role,
            ]);

            // Load relasi user untuk broadcast
            $message->load('user');

            // Log sebelum broadcast
            Log::info('Broadcasting message', [
                'message_id' => $message->id,
                'helpdesk_id' => $helpdesk_id,
                'sender_type' => $role,
                'channel' => 'chat.' . $helpdesk_id
            ]);

            // Broadcast event - PENTING: JANGAN gunakan toOthers()
            // agar pesan terkirim ke semua subscriber termasuk user
            broadcast(new MessageSent($message, $role));

            Log::info('Message broadcasted successfully');

            return response()->json([
                'success' => true,
                'message' => 'Pesan berhasil dikirim',
                'data' => $message
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error sending message: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim pesan: ' . $e->getMessage()
            ], 500);
        }
    }
}
