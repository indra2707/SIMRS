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
    /**
     * Get all messages for a helpdesk ticket
     */
    public function index($helpdesk_id)
    {
        try {
            $user = Auth::user();

            Log::info('📥 Loading messages', [
                'helpdesk_id' => $helpdesk_id,
                'user_id' => $user->id,
                'username' => $user->username
            ]);

            // Cek helpdesk exists
            $helpdesk = HelpDesk::find($helpdesk_id);

            if (!$helpdesk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Helpdesk tidak ditemukan'
                ], 404);
            }

            // ✅ TIDAK ADA AUTHORIZATION CHECK - biarkan simple
            // User frontend sudah difilter, jadi hanya lihat helpdesk mereka sendiri

            // Load messages dengan relasi user
            $messages = Message::with('user')
                ->where('helpdesk_id', $helpdesk_id)
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function($message) {
                    return [
                        'id' => $message->id,
                        'helpdesk_id' => $message->helpdesk_id,
                        'user_id' => $message->user_id,
                        'message' => $message->message,
                        'sender_type' => $message->sender_type, // username
                        'created_at' => $message->created_at,
                        'user' => [
                            'id' => $message->user->id,
                            'username' => $message->user->username,
                            'nama_lengkap' => $message->user->nama_lengkap,
                            'role' => $message->user->role,
                        ],
                        // Helper untuk UI
                        'is_admin' => $message->user->role !== 'user',
                        'display_name' => $message->user->nama_lengkap ?? $message->user->username,
                    ];
                });

            Log::info('✅ Messages loaded', ['count' => $messages->count()]);

            return response()->json($messages);

        } catch (\Exception $e) {
            Log::error('❌ Error loading messages', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat pesan'
            ], 500);
        }
    }

    /**
     * Send a new message
     */
    public function send(Request $request, $helpdesk_id)
    {
        try {
            $request->validate([
                'message' => 'required|string|max:5000'
            ]);

            $user = Auth::user();

            Log::info('📤 Sending message', [
                'user_id' => $user->id,
                'username' => $user->username,
                'role' => $user->role,
                'helpdesk_id' => $helpdesk_id
            ]);

            // Cek helpdesk exists
            $helpdesk = HelpDesk::find($helpdesk_id);

            if (!$helpdesk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Helpdesk tidak ditemukan'
                ], 404);
            }

            // ✅ TIDAK ADA AUTHORIZATION CHECK - keep it simple!

            // Create message
            $message = Message::create([
                'helpdesk_id' => $helpdesk_id,
                'user_id' => $user->id,
                'message' => $request->message,
                'sender_type' => $user->username, // ✅ Simpan username
            ]);

            Log::info('💾 Message created', [
                'message_id' => $message->id,
                'sender_username' => $user->username
            ]);

            // Load relasi
            $message->load('user');

            // Broadcast
            broadcast(new MessageSent($message));

            Log::info('✅ Message broadcasted');

            // Return response
            return response()->json([
                'success' => true,
                'message' => 'Pesan berhasil dikirim',
                'data' => [
                    'id' => $message->id,
                    'helpdesk_id' => $message->helpdesk_id,
                    'user_id' => $message->user_id,
                    'message' => $message->message,
                    'sender_type' => $message->sender_type, // username
                    'created_at' => $message->created_at->toISOString(),
                    'user' => [
                        'id' => $user->id,
                        'username' => $user->username,
                        'nama_lengkap' => $user->nama_lengkap,
                        'role' => $user->role,
                    ],
                    'is_admin' => $user->role !== 'user',
                    'display_name' => $user->nama_lengkap ?? $user->username,
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('❌ Error sending message', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim pesan'
            ], 500);
        }
    }
}
