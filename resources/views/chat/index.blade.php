<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ruang Chat') }}
        </h2>
    </x-slot>

    <div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="card-title mb-0">Ruang Chat</h2>
                        @if(auth()->user()->hasRole('admin'))
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoomModal">
                                Buat Ruang Chat Baru
                            </button>
                        @endif
                    </div>

                    <div class="row g-4">
                        @foreach($rooms as $room)
                            <div class="col-md-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $room->name }}</h5>
                                        <p class="card-text text-muted mb-4">{{ $room->description }}</p>
                                        <a href="{{ route('chat.showing', $room) }}" class="btn btn-success w-100">
                                            Masuk Chat
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Room Modal -->
<div class="modal fade" id="createRoomModal" tabindex="-1" aria-labelledby="createRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createRoomModalLabel">Buat Ruang Chat Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('chat.create-room') }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Ruangan</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Buat Ruangan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize Pusher
    const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
        cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}'
    });

    // Subscribe to all chat rooms
    @foreach($rooms as $room)
        const channel{{ $room->id }} = pusher.subscribe('chat-room-{{ $room->id }}');
        channel{{ $room->id }}.bind('new-message', function(data) {
            const message = data.message;
            if (document.getElementById('chat-messages')) {
                const messageHtml = `
                    <div class="mb-3 text-${message.is_admin ? 'end' : 'start'}">
                        <div class="d-inline-block p-3 rounded ${message.is_admin ? 'bg-primary text-white' : 'bg-light'} position-relative" style="max-width: 75%;">
                            <strong>${message.user.name}</strong>
                            <p class="mb-1">${message.message}</p>
                            <small class="${message.is_admin ? 'text-white-50' : 'text-muted'}">Baru saja</small>
                            ${message.user.id === {{ auth()->id() }} ? 
                                '<button onclick="deleteMessage(' + message.id + ')" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" style="padding: 0.1rem 0.4rem;">×</button>' : 
                                ''}
                        </div>
                    </div>
                `;
                const chatMessages = document.getElementById('chat-messages');
                chatMessages.insertAdjacentHTML('beforeend', messageHtml);
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        });
    @endforeach
</script>
@endpush
</x-app-layout>
