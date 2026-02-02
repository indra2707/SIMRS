<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\Controller;
use App\Models\Logistik\LogistikMessage;
use App\Models\Logistik\Permintaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserChatController extends Controller
{
     public function index($permintaan_id)
    {
        try {
            $user = Auth::user();

            Log::info('📥 Loading messages', [
                'helpdesk_id' => $permintaan_id,
                'user_id' => $user->id,
                'username' => $user->username
            ]);

            // Cek helpdesk exists
            $permintaan = LogistikMessage::find($permintaan_id);

            if (!$permintaan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Helpdesk tidak ditemukan'
                ], 404);
            }

            // ✅ TIDAK ADA AUTHORIZATION CHECK - biarkan simple
            // User frontend sudah difilter, jadi hanya lihat helpdesk mereka sendiri

            // Load messages dengan relasi user
            $messages = LogistikMessage::with('user')
                ->where('permintaan_id', $permintaan_id)
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function ($message) {
                    return [
                        'id' => $message->id,
                        'permintaan_id' => $message->permintaan_id,
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
    public function send(Request $request, $permintaan_id)
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
                'helpdesk_id' => $permintaan_id
            ]);

            // Cek helpdesk exists
            $permintaan = Permintaan::find($permintaan_id);

            if (!$permintaan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Permintaan tidak ditemukan'
                ], 404);
            }

            // ✅ TIDAK ADA AUTHORIZATION CHECK - keep it simple!

            // Create message
            $message = LogistikMessage::create([
                'helpdesk_id' => $permintaan_id,
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
            // broadcast(new MessageSent($message));

            // Log::info('✅ Message broadcasted');

            // Return response
            return response()->json([
                'success' => true,
                'message' => 'Pesan berhasil dikirim',
                'data' => [
                    'id' => $message->id,
                    'helpdesk_id' => $message->permintaan_id,
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

    public function getOpponentName($permintaanId)
    {
        $opponent = LogistikMessage::where('permintaan_id', $permintaanId)
            ->where('user_id', '!=', auth()->id())
            ->join('users', 'users.id', '=', 'messages.user_id')
            ->select('users.username', 'users.nama_lengkap')
            ->first();

        // fallback jika belum ada pesan
        if (!$opponent) {
            $permintaan = Permintaan::with('user')->find($permintaanId);

            if ($permintaan && $permintaan->user) {
                $opponent = (object) [
                    'username' => $permintaan->user->username,
                    'nama_lengkap' => $permintaan->user->nama_lengkap,
                ];
            } else {
                $opponent = (object) [
                    'username' => 'Unknown',
                    'nama_lengkap' => '',
                ];
            }
        }

        return response()->json([
            'username' => $opponent->username,
            'nama_lengkap' => $opponent->nama_lengkap
        ]);
    }
}
