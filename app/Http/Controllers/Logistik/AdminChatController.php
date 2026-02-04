<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\Controller;
use App\Models\Logistik\LogistikMessage;
use App\Models\Logistik\Permintaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminChatController extends Controller
{
    public function index($permintaan_id)
    {
        try {
            $user = Auth::user();

            Log::info('Admin loading messages', [
                'permintaan_id' => $permintaan_id,
                'admin_id' => $user->id,
                'admin_username' => $user->username
            ]);

            // Cek permintaan exists
            $permintaan = Permintaan::find($permintaan_id);

            if (!$permintaan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Permintaan tidak ditemukan'
                ], 404);
            }

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
                        'sender_type' => $message->sender_type,
                        'created_at' => $message->created_at,
                        'user' => [
                            'id' => $message->user->id,
                            'username' => $message->user->username,
                            'nama_lengkap' => $message->user->nama_lengkap ?? $message->user->username,
                            'role' => $message->user->role,
                        ],
                        // Helper untuk UI
                        'is_admin' => in_array($message->user->role, ['admin', 'superadmin', 'support']),
                        'display_name' => $message->user->nama_lengkap ?? $message->user->username,
                    ];
                });

            Log::info('✅ Admin messages loaded', ['count' => $messages->count()]);

            return response()->json($messages);

        } catch (\Exception $e) {
            Log::error('❌ Error loading admin messages', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat pesan'
            ], 500);
        }
    }

    public function send(Request $request, $permintaan_id)
    {
        try {
            $request->validate([
                'message' => 'required|string|max:5000'
            ]);

            $user = Auth::user();

            Log::info('📤 Admin sending message', [
                'admin_id' => $user->id,
                'admin_username' => $user->username,
                'admin_role' => $user->role,
                'permintaan_id' => $permintaan_id
            ]);

            // Cek permintaan exists
            $permintaan = Permintaan::find($permintaan_id);

            if (!$permintaan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Permintaan tidak ditemukan'
                ], 404);
            }

            // Create message
            $message = LogistikMessage::create([
                'permintaan_id' => $permintaan_id,
                'user_id' => $user->id,
                'message' => $request->message,
                'sender_type' => $user->role,
            ]);

            Log::info('Admin message created', [
                'message_id' => $message->id,
                'sender_role' => $user->role
            ]);

            // Load relasi
            $message->load('user');

            // broadcast(new MessageSent($message))->toOthers();

            // Return response
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
                    'is_admin' => true,
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
            Log::error('❌ Error admin sending message', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim pesan'
            ], 500);
        }
    }

    /**
     * Get info user (pembuat permintaan) untuk header chat admin
     */
    public function getUserInfo($permintaan_id)
    {
        try {
            $permintaan = Permintaan::with('user')->find($permintaan_id);

            if (!$permintaan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Permintaan tidak ditemukan'
                ], 404);
            }

            $user = $permintaan->user;

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'username' => $user->username,
                'nama_lengkap' => $user->nama_lengkap ?? $user->username,
                'role' => $user->role,
                'nama_permintaan' => $permintaan->nama_permintaan ?? 'Permintaan Logistik',
                'no_surat' => $permintaan->no_surat ?? '-',
                'status' => $permintaan->status ?? '-',
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Error getting user info', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat info user'
            ], 500);
        }
    }
}
