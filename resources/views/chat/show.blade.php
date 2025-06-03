<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ 'Ruang Chat: ' . $room->name }}
        </h2>
    </x-slot>

    <div class="container mt-5">
        <div class="card">
            <div class="card-body" style="height: 600px; display: flex; flex-direction: column;">
                <div id="chat-messages" class="overflow-auto mb-3" style="flex-grow: 1;">
                    @foreach($messages as $message)
                        <div class="mb-3 text-{{ $message->is_admin ? 'end' : 'start' }}" data-message-id="{{ $message->id }}">
                            <div class="d-inline-block p-3 rounded {{ $message->is_admin ? 'bg-primary text-white' : 'bg-light' }} position-relative" style="max-width: 75%;">
                                <strong>{{ $message->user->name }}</strong>
                                <p class="mb-1">{{ $message->message }}</p>
                                <small class="{{ $message->is_admin ? 'text-white-50' : 'text-muted' }}">{{ $message->created_at->diffForHumans() }}</small>
                                @if(auth()->user()->hasRole('admin') || auth()->id() === $message->user_id)
                                    <button onclick="deleteMessage({{ $message->id }})" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" style="padding: 0.1rem 0.4rem;">×</button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div id="message-form" class="d-flex gap-2">
                    @csrf
                    <input type="text" id="message-input" name="message" placeholder="Ketik pesan Anda..." required
                           class="form-control" autocomplete="off" />
                    <button type="button" id="send-button" class="btn btn-primary">Kirim</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        // Configuration variables
        const currentUserId = {{ auth()->id() }};
        const isAdmin = {{ auth()->user()->hasRole('admin') ? 'true' : 'false' }};
        const roomId = {{ $room->id }};
        const csrfToken = document.querySelector('input[name="_token"]').value;

        // Initialize Pusher with better error handling
        console.log('Initializing Pusher...');
        console.log('Pusher Key:', '{{ config('broadcasting.connections.pusher.key') }}');
        console.log('Pusher Cluster:', '{{ config('broadcasting.connections.pusher.options.cluster') }}');

        let pusher = null;
        let channel = null;

        try {
            pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
                cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
                encrypted: true,
                enabledTransports: ['ws', 'wss'],
                disabledTransports: [],
                authEndpoint: '/broadcasting/auth',
                auth: {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                }
            });

            // Enable Pusher logging for debugging
            Pusher.logToConsole = true;

            // Subscribe to chat room channel
            const channelName = `chat-room-${roomId}`;
            console.log('Subscribing to channel:', channelName);

            channel = pusher.subscribe(channelName);

            // Channel event handlers
            channel.bind('pusher:subscription_succeeded', () => {
                console.log('✅ Successfully subscribed to channel:', channelName);
            });

            channel.bind('pusher:subscription_error', (error) => {
                console.error('❌ Failed to subscribe to channel:', error);
            });

            // Pusher connection state
            pusher.connection.bind('connected', () => {
                console.log('✅ Pusher connected');
            });

            pusher.connection.bind('disconnected', () => {
                console.log('❌ Pusher disconnected');
            });

            pusher.connection.bind('error', (error) => {
                console.error('❌ Pusher connection error:', error);
            });

            function addMessageToChat(message, isCurrentUser = false) {
            const messageHtml = `
                <div class="mb-3 text-${message.is_admin ? 'end' : 'start'}" data-message-id="${message.id}">
                    <div class="d-inline-block p-3 rounded ${message.is_admin ? 'bg-primary text-white' : 'bg-light'} position-relative" style="max-width: 75%;">
                        <strong>${escapeHtml(message.user.name)}</strong>
                        <p class="mb-1">${escapeHtml(message.message)}</p>
                        <small class="${message.is_admin ? 'text-white-50' : 'text-muted'}">${message.created_at}</small>
                        ${(isCurrentUser || isAdmin) ?
                            `<button onclick="deleteMessage(${message.id})" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" style="padding: 0.1rem 0.4rem;">×</button>` :
                            ''}
                    </div>
                </div>
            `;
            const chatMessages = document.getElementById('chat-messages');
            chatMessages.insertAdjacentHTML('beforeend', messageHtml);
            scrollToBottom();
        }

        // Function to escape HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Function to scroll to bottom
        function scrollToBottom() {
            const chatMessages = document.getElementById('chat-messages');
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        
            // Handle new messages from Pusher
            channel.bind('new-message', function(data) {
                console.log('📨 Received new message via Pusher:', data);

                if (!data.message) {
                    console.error('Invalid message data received');
                    return;
                }

                const message = data.message;

                // Always show messages from other users
                if (message.user_id !== currentUserId) {
                    console.log('Adding message from other user:', message.user.name);
                    addMessageToChat(message);
                    playNotificationSound();
                } else {
                    console.log('Message from current user, skipping (already displayed)');
                }
            });

            // Handle message deletion from Pusher
            channel.bind('message-deleted', function(data) {
                console.log('🗑️ Message deleted via Pusher:', data);

                if (data.message_id) {
                    const messageDiv = document.querySelector(`[data-message-id="${data.message_id}"]`);
                    if (messageDiv) {
                        messageDiv.remove();
                        console.log('Message removed from DOM');
                    }
                }
            });

        } catch (error) {
            console.error('❌ Failed to initialize Pusher:', error);
            console.log('Chat will work without real-time updates');
        }

        // Function to play notification sound
        function playNotificationSound() {
            try {
                // Create a simple notification sound using Web Audio API
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);

                oscillator.frequency.value = 800;
                gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.2);

                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.2);
            } catch (error) {
                console.log('Could not play notification sound:', error);
            }
        }

        // Handle message sending
        const messageForm = document.getElementById('message-form');
        const messageInput = document.getElementById('message-input');
        const sendButton = document.getElementById('send-button');

        // Function to send message
        async function sendMessage() {
            const message = messageInput.value.trim();

            if (!message) {
                messageInput.focus();
                return;
            }

            // Disable form while sending
            sendButton.disabled = true;
            messageInput.disabled = true;
            sendButton.textContent = 'Mengirim...';

            try {
                console.log('Sending message:', message);

                const formData = new FormData();
                formData.append('message', message);
                formData.append('_token', csrfToken);

                const response = await fetch('{{ route('chat.store', $room) }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                console.log('Response status:', response.status);

                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Response error:', errorText);
                    throw new Error(`HTTP ${response.status}: ${errorText}`);
                }

                const data = await response.json();
                console.log('Response data:', data);

                if (!data.success) {
                    throw new Error(data.message || 'Failed to send message');
                }

                console.log('✅ Message sent successfully:', data);

                // Add the sent message immediately
                addMessageToChat(data.message, true);
                messageInput.value = '';

            } catch (error) {
                console.error('❌ Error sending message:', error);
                alert(`Gagal mengirim pesan: ${error.message}`);
            } finally {
                sendButton.disabled = false;
                messageInput.disabled = false;
                sendButton.textContent = 'Kirim';
                messageInput.focus();
            }
        }

        // Button click handler
        sendButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            sendMessage();
        });

        // Handle message deletion
        async function deleteMessage(messageId) {
            if (!confirm('Apakah Anda yakin ingin menghapus pesan ini?')) return;

            try {
                const response = await fetch(`/chat/message/${messageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || 'Failed to delete message');
                }

                const data = await response.json();

                if (data.success) {
                    // Remove message locally (Pusher will handle it for other users)
                    const messageDiv = document.querySelector(`[data-message-id="${messageId}"]`);
                    if (messageDiv) {
                        messageDiv.remove();
                    }
                    console.log('✅ Message deleted successfully');
                } else {
                    throw new Error(data.message || 'Failed to delete message');
                }
            } catch (error) {
                console.error('❌ Error deleting message:', error);
                alert(`Gagal menghapus pesan: ${error.message}`);
            }
        }

        // Auto-focus on message input
        document.getElementById('message-input').focus();

        // Scroll to bottom on page load
        window.addEventListener('load', () => {
            scrollToBottom();
        });

        // Handle Enter key to send message
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                e.stopPropagation();
                sendMessage();
            }
        });
    </script>
    @endpush
</x-app-layout>