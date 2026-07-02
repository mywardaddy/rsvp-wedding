<x-app-layout>
    <x-slot name="header">Detail Tamu</x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Guest Info -->
            <div class="lg:col-span-2 glass-card p-6">
                <div class="flex items-start justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white text-xl font-bold shadow-lg">
                            {{ strtoupper(substr($guest->name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">{{ $guest->name }}</h3>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="badge {{ $guest->category === 'vip' ? 'badge-vip' : 'badge-gold' }}">{{ $guest->category_label }}</span>
                                @if($guest->guestGroup)
                                <span class="text-xs text-gray-400">· {{ $guest->guestGroup->name }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('pengantin.guests.edit', $guest) }}" class="btn-outline text-xs"><i class="fas fa-edit"></i></a>
                        <a href="{{ route('invitation.show', $guest->slug) }}" target="_blank" class="btn-sage text-xs"><i class="fas fa-external-link-alt"></i> Undangan</a>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="p-3 rounded-xl bg-white/50">
                        <p class="text-xs text-gray-400 mb-1"><i class="fas fa-phone mr-1"></i>Telepon</p>
                        <p class="text-sm font-medium text-gray-700">{{ $guest->phone ?? '-' }}</p>
                    </div>
                    <div class="p-3 rounded-xl bg-white/50">
                        <p class="text-xs text-gray-400 mb-1"><i class="fas fa-envelope mr-1"></i>Email</p>
                        <p class="text-sm font-medium text-gray-700">{{ $guest->email ?? '-' }}</p>
                    </div>
                    <div class="p-3 rounded-xl bg-white/50">
                        <p class="text-xs text-gray-400 mb-1"><i class="fas fa-users mr-1"></i>Maks. Pendamping</p>
                        <p class="text-sm font-medium text-gray-700">{{ $guest->max_companions }} orang</p>
                    </div>
                    <div class="p-3 rounded-xl bg-white/50">
                        <p class="text-xs text-gray-400 mb-1"><i class="fas fa-map-marker-alt mr-1"></i>Alamat</p>
                        <p class="text-sm font-medium text-gray-700">{{ $guest->address ?? '-' }}</p>
                    </div>
                </div>

                <!-- RSVP Status -->
                <div class="p-4 rounded-xl {{ $guest->rsvp ? ($guest->rsvp->status === 'hadir' ? 'bg-green-50' : 'bg-red-50') : 'bg-gray-50' }} mb-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Status RSVP</h4>
                    @if($guest->rsvp)
                    <div class="flex items-center gap-3">
                        <span class="badge {{ $guest->rsvp->status === 'hadir' ? 'badge-hadir' : 'badge-tidak-hadir' }} text-sm px-3 py-1.5">
                            {{ $guest->rsvp->status === 'hadir' ? '✓ Hadir' : '✗ Tidak Hadir' }}
                            @if($guest->rsvp->status === 'hadir') ({{ $guest->rsvp->number_of_guests }} orang) @endif
                        </span>
                        <span class="text-xs text-gray-400">{{ $guest->rsvp->responded_at->format('d M Y H:i') }}</span>
                    </div>
                    @if($guest->rsvp->message)
                    <p class="text-sm text-gray-600 mt-2 italic">"{{ $guest->rsvp->message }}"</p>
                    @endif
                    @else
                    <p class="text-sm text-gray-500">Belum memberikan konfirmasi RSVP</p>
                    @endif
                </div>

                <!-- Checkin History -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Riwayat Check-in</h4>
                    @forelse($guest->checkins as $checkin)
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-green-50/50">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-700">{{ $checkin->checked_in_at->format('d M Y H:i') }}</p>
                            <p class="text-xs text-gray-400">oleh {{ $checkin->scanner->name }} · {{ $checkin->method }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-400 p-3">Belum check-in</p>
                    @endforelse
                </div>
            </div>

            <!-- QR Code Card -->
            <div class="glass-card p-6 text-center">
                <h4 class="text-sm font-semibold text-gray-700 mb-4">QR Code Tamu</h4>

                <div class="bg-white rounded-2xl p-6 shadow-inner mb-4 inline-block">
                    {!! $qrSvg !!}
                </div>

                <p class="text-xs font-mono text-gray-500 mb-4">{{ $guest->qr_code }}</p>

                <div class="space-y-2">
                    <form action="{{ route('pengantin.guests.generate-qr', $guest) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-gold w-full justify-center">
                            <i class="fas fa-sync-alt"></i> Regenerate QR
                        </button>
                    </form>
                    <a href="{{ route('invitation.ticket', $guest->slug) }}" target="_blank" class="btn-outline w-full justify-center inline-flex">
                        <i class="fas fa-ticket-alt"></i> Lihat Tiket
                    </a>
                </div>

                @if($guest->rsvp && $guest->rsvp->status === 'hadir')
                <div class="mt-4 p-3 rounded-xl bg-green-50">
                    <p class="text-xs text-green-700 font-medium">
                        <i class="fas fa-check-circle mr-1"></i>Tamu akan hadir
                        <br>{{ $guest->rsvp->number_of_guests }} orang
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
