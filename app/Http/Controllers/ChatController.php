<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;

class ChatController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $superAdmin = User::whereHas('roles', function ($query) {
            $query->where('name', 'super_admin');
        })->first();

        if (!$superAdmin) {
            return abort(403, 'Super admin tidak ditemukan.');
        }

        $chatRooms = ChatRoom::where('user_id', $userId)
                            ->orWhere('admin_id', $userId)
                            ->with('user', 'admin')
                            ->get();

        $chatRoom = $chatRooms->first();

        $chats = $chatRoom ? Chat::where('chat_room_id', $chatRoom->id)
                            ->orderBy('created_at', 'asc')
                            ->get() : [];

        return view('chat.index', compact('chats', 'superAdmin', 'chatRoom', 'chatRooms'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required',
            'receiver_id' => 'required|exists:users,id'
        ]);
    
        $sender = Auth::user();
        $receiver = User::findOrFail($request->receiver_id);
    
        // Cari atau buat chat room antara sender dan receiver
        $chatRoom = ChatRoom::where(function ($query) use ($sender, $receiver) {
            $query->where('user_id', $sender->id)
                  ->where('admin_id', $receiver->id);
        })->orWhere(function ($query) use ($sender, $receiver) {
            $query->where('user_id', $receiver->id)
                  ->where('admin_id', $sender->id);
        })->first();
    
        if (!$chatRoom) {
            $chatRoom = ChatRoom::create([
                'user_id' => min($sender->id, $receiver->id),
                'admin_id' => max($sender->id, $receiver->id)
            ]);
        }
    
        // Simpan pesan ke chat room yang sesuai
        $chat = Chat::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'message' => $request->message,
            'chat_room_id' => $chatRoom->id
        ]);
    
        // Kirim pesan secara real-time dengan Pusher
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            ['cluster' => env('PUSHER_APP_CLUSTER')]
        );
    
        $pusher->trigger("chat-channel-{$chatRoom->id}", 'new-message', [
            'sender' => $sender->name,
            'message' => $chat->message
        ]);
    
        return response()->json($chat);
    }
    



    public function deleteMessage($id)
    {
        $chat = Chat::findOrFail($id);
        if ($chat->sender_id !== Auth::id()) {
            return response()->json(['error' => 'Tidak diizinkan'], 403);
        }

        $chat->delete();
        return response()->json(['success' => 'Pesan berhasil dihapus']);
    }

    public function updateMessage(Request $request, $id)
    {
        $request->validate(['message' => 'required']);

        $chat = Chat::findOrFail($id);
        if ($chat->sender_id !== Auth::id()) {
            return response()->json(['error' => 'Tidak diizinkan'], 403);
        }

        $chat->update(['message' => $request->message]);

        return response()->json(['success' => 'Pesan berhasil diperbarui']);
    }

    public function closeChat($id)
    {
        $chatRoom = ChatRoom::findOrFail($id);
        $chatRoom->delete();

        return response()->json(['success' => 'Chat berhasil ditutup']);
    }
    public function show($roomId)
    {
        $chatRoom = ChatRoom::find($roomId);

        if (!$chatRoom) {
            return response()->json(['message' => 'Chat room not found'], 404);
        }

        $chats = $chatRoom->chats()->orderBy('created_at', 'asc')->get();

        return view('chat.partials.chat_messages', compact('chats'))->render();
    }

}
