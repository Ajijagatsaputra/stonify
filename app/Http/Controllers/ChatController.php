<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Pusher\Pusher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    protected $pusher;

    public function __construct()
    {
        $this->pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            [
                'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                'useTLS' => true
            ]
        );
    }

    public function index()
    {
        if (auth()->user()->hasRole('admin')) {
            $rooms = ChatRoom::where('is_active', true)->get();
            return view('chat.index', compact('rooms'));
        } else {
            return $this->start();
        }
    }

    public function start()
    {
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();
        $rooms = ChatRoom::where('user_id', auth()->id())
                        ->where('is_active', true)
                        ->withCount('messages')
                        ->get();

        return view('chat.start', compact('admins', 'rooms'));
    }

    public function startChat($adminId)
    {
        $user = auth()->user();
        // Cek apakah sudah ada room antara user dan admin
        $room = ChatRoom::where(function($q) use ($user, $adminId) {
            $q->where('user_id', $user->id)->where('admin_id', $adminId);
        })->orWhere(function($q) use ($user, $adminId) {
            $q->where('user_id', $adminId)->where('admin_id', $user->id);
        })->first();

        if (!$room) {
            $room = ChatRoom::create([
                'user_id' => $user->id,
                'admin_id' => $adminId,
                'slug' => Str::slug('Percakapan dengan ' . User::find($adminId)->name . ' - ' . $user->name),
                'is_active' => true,
                'name' => 'Percakapan dengan ' . User::find($adminId)->name . ' - ' . $user->name,
            ]);
        }

        return redirect()->route('chat.showing', $room->slug);
    }


    public function show($roomSlug)
    {
        $user = Auth::user();

        // Try to find room by slug
        $room = ChatRoom::where('slug', $roomSlug)->first();

        if (!$room) {
            // If not found by slug, and user is not admin, treat slug as admin ID to create/find room
            if (!$user->hasRole('admin') && preg_match('/^chat-(\d+)-(\d+)$/', $roomSlug, $matches)) {
                $adminId = $matches[2];
                $existingRoom = ChatRoom::where('user_id', $user->id)
                    ->where('admin_id', $adminId)
                    ->where('is_active', true)
                    ->first();

                if (!$existingRoom) {
                    $admin = User::findOrFail($adminId);
                    $existingRoom = ChatRoom::create([
                        'user_id' => $user->id,
                        'admin_id' => $admin->id,
                        'name' => 'Chat dengan ' . $admin->name,
                        'slug' => $roomSlug,
                        'is_active' => true
                    ]);
                }

                $room = $existingRoom;
            } else {
                abort(404, 'Chat room not found');
            }
        }

        // Authorization check
        if ($room->user_id !== $user->id && !$user->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }

        $messages = $room->messages()->with('user')->latest()->take(50)->get()->reverse();
        return view('chat.show', compact('room', 'messages'));
    }

    public function store(Request $request, ChatRoom $room)
    {
        // Log untuk debugging
        Log::info('Chat store request received', [
            'room_id' => $room->id,
            'user_id' => Auth::id(),
            'is_ajax' => $request->ajax(),
            'content_type' => $request->header('Content-Type'),
            'data' => $request->all()
        ]);

        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $message = $room->messages()->create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'is_admin' => Auth::user()->hasRole('admin')
        ]);

        $message->load('user');

        // Prepare message data
        $messageData = [
            'id' => $message->id,
            'message' => $message->message,
            'user_id' => $message->user_id,
            'is_admin' => $message->is_admin,
            'created_at' => $message->created_at->diffForHumans(),
            'user' => [
                'id' => $message->user->id,
                'name' => $message->user->name
            ]
        ];

        // Trigger Pusher event
        $channelName = 'chat-room-' . $room->id;

        try {
            $response = $this->pusher->trigger($channelName, 'new-message', [
                'message' => $messageData
            ]);

            Log::info('Pusher event triggered successfully', [
                'channel' => $channelName,
                'event' => 'new-message',
                'message_id' => $message->id,
                'pusher_response' => $response
            ]);
        } catch (\Exception $e) {
            Log::error('Pusher error: ' . $e->getMessage(), [
                'channel' => $channelName,
                'message_id' => $message->id,
                'error' => $e->getTraceAsString()
            ]);
        }

        // Always return JSON response
        return response()->json([
            'success' => true,
            'message' => $messageData
        ]);
    }

    public function createRoom(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000'
        ]);

        $room = ChatRoom::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description
        ]);

        return redirect()->route('chat.showing', $room->slug);
    }

    public function deleteMessage(ChatMessage $message)
    {
        if (auth()->user()->hasRole('admin') || auth()->id() === $message->user_id) {
            $roomId = $message->room_id;
            $messageId = $message->id;

            $message->delete();

            // Trigger Pusher event for message deletion
            try {
                $this->pusher->trigger('chat-room-' . $roomId, 'message-deleted', [
                    'message_id' => $messageId
                ]);
            } catch (\Exception $e) {
                Log::error('Pusher delete error: ' . $e->getMessage());
            }

            return response()->json(['success' => true]);
        }
        return response()->json(['error' => 'Unauthorized'], 403);
    }
}