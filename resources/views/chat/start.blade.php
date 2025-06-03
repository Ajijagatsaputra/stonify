<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mulai Percakapan') }}
        </h2>
    </x-slot>

    <div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title mb-4">Mulai Percakapan Baru</h3>
                    <p class="text-muted mb-4">
                        Pilih admin yang ingin Anda ajak berbicara atau mulai percakapan baru.
                    </p>

                    <div class="row g-4">
                        @foreach($admins as $admin)
                            <div class="col-md-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                                <span class="fs-4">{{ substr($admin->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <h5 class="card-title mb-1">{{ $admin->name }}</h5>
                                                <p class="card-text text-muted small">{{ $admin->email }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('chat.showing', ['room' => $admin->id]) }}" 
                                           class="btn btn-primary w-100">
                                            Mulai Chat
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($rooms->isNotEmpty())
                        <div class="mt-5">
                            <h3 class="mb-4">Percakapan Aktif</h3>
                            <div class="row g-4">
                                @foreach($rooms as $room)
                                    <div class="col-md-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $room->name }}</h5>
                                                <p class="card-text text-muted">
                                                    {{ $room->messages_count ?? 0 }} pesan
                                                </p>
                                                <a href="{{ route('chat.showing', $room) }}" 
                                                   class="btn btn-outline-primary w-100">
                                                    Lanjutkan
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
