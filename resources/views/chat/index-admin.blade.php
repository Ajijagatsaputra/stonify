<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Live Chat') }}
        </h2>
    </x-slot>

    <div class="py-8 mt-5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="chat-container mx-auto p-6">
                <input type="hidden" id="receiver-id" value="">

                <div id="chat-tabs" class="flex space-x-2 border-b pb-2">
                    @foreach ($chatRooms as $room)
                        @php
                            $chatUser = ($room->user_id == auth()->id()) ? $room->admin : $room->user;
                        @endphp
                        @if ($chatUser)
                            <div id="tab-{{ $room->id }}" class="relative bg-gray-300 px-4 py-2 rounded flex items-center">
                                <button onclick="openChat({{ $room->id }}, {{ $chatUser->id }})">
                                    {{ $chatUser->name }}
                                </button>
                                <button onclick="closeChat({{ $room->id }})" class="ml-2 text-red-500 text-sm">✖</button>
                            </div>
                        @endif
                    @endforeach
                </div>
                    <div id="chat-container" class="chat-body h-64 overflow-y-auto bg-gray-50 p-4">
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
                    </div>
                    <div class="chat-footer flex items-center border-t p-4">
                        <input type="text" id="chat-input" class="form-input w-full rounded-md border-gray-300 mr-2" placeholder="Ketik pesan..." autofocus>
                        <button id="send-btn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-300">
                            Kirim
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        function openChat(roomId, userId) {
            document.getElementById('receiver-id').value = userId; // Simpan user ID

            fetch(`/chat/${roomId}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('chat-container').innerHTML = html;
                });
        }

        function deleteMessage(id) {
            fetch(`/chat/delete/${id}`, { method: "DELETE", headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"}})
                .then(() => document.getElementById(`message-${id}`).parentNode.remove());
        }

        function editMessage(id) {
            let newMessage = prompt("Edit pesan:");
            if (newMessage) {
                fetch(`/chat/update/${id}`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ message: newMessage })
                }).then(() => document.getElementById(`message-${id}`).innerText = newMessage);
            }
        }

        const chatInput = document.getElementById('chat-input');
        const sendBtn = document.getElementById('send-btn');

        sendBtn.addEventListener('click', sendMessage);
        chatInput.addEventListener('keypress', function (event) {
            if (event.key === 'Enter') {
                sendMessage();
            }
        });

        function sendMessage() {
    const message = document.getElementById('chat-input').value.trim();
    const receiverId = document.getElementById('receiver-id').value; // Ambil receiver ID

    if (message && receiverId) {
        fetch("{{ route('chat.send') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ message, receiver_id: receiverId })
        }).then(() => {
            document.getElementById('chat-input').value = "";
        });
    }
}

        @if(isset($chatRoom))
            const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
                cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
                encrypted: true
            });

            const channel = pusher.subscribe("chat-channel-{{ $chatRoom->id }}");
            channel.bind("new-message", function (data) {
                const chatBody = document.getElementById('chat-container');
                const messageDiv = document.createElement("div");
                messageDiv.className = "chat-message bot mb-2";
                messageDiv.innerHTML = `<div class="message bg-gray-200 text-gray-800 p-2 rounded-md">
                                            <strong>${data.sender}:</strong> ${data.message}
                                        </div>`;
                chatBody.appendChild(messageDiv);
                chatBody.scrollTop = chatBody.scrollHeight;
            });
        @endif

    function closeChat(roomId) {
        document.getElementById(`tab-${roomId}`).remove();
        document.getElementById('chat-container').innerHTML = "";
    }
    </script>
</x-app-layout>