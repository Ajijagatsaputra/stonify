@foreach ($chats as $chat)
    <div class="chat-message {{ $chat->sender_id == auth()->id() ? 'user' : 'bot' }} mb-2">
        <div class="message {{ $chat->sender_id == auth()->id() ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800' }} p-2 rounded-md">
        <strong>{{ $chat->sender->name ?? 'User Tidak Diketahui' }}:</strong>
            <span id="message-{{ $chat->id }}">{{ $chat->message }}</span>
            @if($chat->sender_id == auth()->id())
                <button onclick="editMessage({{ $chat->id }})" class="text-sm text-yellow-500">Edit</button>
                <button onclick="deleteMessage({{ $chat->id }})" class="text-sm text-red-500">Hapus</button>
            @endif
        </div>
    </div>
@endforeach
