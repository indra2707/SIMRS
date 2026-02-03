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
                'permintaan_id' => $permintaan_id,
                'user_id' => $user->id,
                'username' => $user->username
            ]);

            $permintaan = Permintaan::find($permintaan_id);

            if (!$permintaan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Permintaan tidak ditemukan'
                ], 404);
            }

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
                        'sender_type' => $message->sender_type,
                        'created_at' => $message->created_at,
                        'user' => [
                            'id' => $message->user->id,
                            'username' => $message->user->username,
                            'nama_lengkap' => $message->user->nama_lengkap ?? $message->user->username,
                            'role' => $message->user->role,
                        ],
                        'is_admin' => in_array($message->user->role, ['admin', 'superadmin', 'support']),
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
                'permintaan_id' => $permintaan_id
            ]);

            $permintaan = Permintaan::find($permintaan_id);

            if (!$permintaan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Permintaan tidak ditemukan'
                ], 404);
            }

            $message = LogistikMessage::create([
                'permintaan_id' => $permintaan_id,
                'user_id' => $user->id,
                'message' => $request->message,
                'sender_type' => $user->username,
            ]);

            Log::info('💾 Message created', [
                'message_id' => $message->id,
                'sender_username' => $user->username
            ]);

            $message->load('user');

            return response()->json([
                'success' => true,
                'message' => 'Pesan berhasil dikirim',
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
                        'nama_lengkap' => $user->nama_lengkap ?? $user->username,
                        'role' => $user->role,
                    ],
                    'is_admin' => in_array($user->role, ['admin', 'superadmin', 'support']),
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

    public function getPermintaanInfo($permintaan_id)
    {
        try {
            Log::info('📋 Getting permintaan info', [
                'permintaan_id' => $permintaan_id,
                'user_id' => Auth::id()
            ]);

            // Load permintaan
            $permintaan = Permintaan::find($permintaan_id);

            if (!$permintaan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Permintaan tidak ditemukan'
                ], 404);
            }

            // Cari lawan bicara dari chat history (paling reliable)
            $opponent = LogistikMessage::where('permintaan_id', $permintaan_id)
                ->where('user_id', '!=', Auth::id())
                ->with('user')
                ->latest()
                ->first();

            if ($opponent && $opponent->user) {
                // Ada opponent dari chat history
                $opponentData = [
                    'username' => $opponent->user->username,
                    'nama_lengkap' => $opponent->user->nama_lengkap ?? $opponent->user->username,
                    'role' => $opponent->user->role ?? 'user',
                ];
            } else {
                $currentUser = Auth::user();

                if (in_array($currentUser->role, ['admin', 'superadmin', 'support'])) {
                    // Admin berbicara dengan User
                    $opponentData = [
                        'username' => $permintaan->created_by ?? 'User',
                        'nama_lengkap' => 'User (' . ($permintaan->created_by ?? 'Unknown') . ')',
                        'role' => 'user',
                    ];
                } else {
                    // User biasa berbicara dengan Admin
                    $opponentData = [
                        'username' => 'Admin Support',
                        'nama_lengkap' => 'Administrator Logistik',
                        'role' => 'admin',
                    ];
                }
            }

            $response = [
                'success' => true,
                'username' => $opponentData['username'],
                'nama_lengkap' => $opponentData['nama_lengkap'],
                'role' => $opponentData['role'],
                'nama_permintaan' => $permintaan->nama_permintaan ?? 'Permintaan Logistik',
                'no_surat' => $permintaan->no_surat ?? '-',
                'status' => $permintaan->status ?? '-',
                'is_online' => false,
                'last_seen' => null,
            ];


            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('❌ Error getting permintaan info', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            // Fallback response - selalu berhasil
            return response()->json([
                'success' => true,
                'username' => 'Support',
                'nama_lengkap' => 'Tim Support Logistik',
                'role' => 'admin',
                'nama_permintaan' => 'Permintaan Logistik',
                'no_surat' => '-',
                'status' => '-',
                'is_online' => false,
                'last_seen' => null,
            ]);
        }
    }
}
