<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $event->title }} - Undangan</title>
    <meta name="description" content="Undangan pernikahan {{ $event->groom_name }} & {{ $event->bride_name }}">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700,800|playfair-display:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        :root { --gold: #C9B037; --sage: #9CAF88; --dark: #2C2C2C; --cream: #FAFAF5; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--cream); color: var(--dark); overflow-x: hidden; }
        .serif { font-family: 'Playfair Display', serif; }

        /* Cover Section */
        .cover {
            min-height: 100vh; display: flex; flex-direction: column;
            align-items: center; justify-content: center; text-align: center;
            background: linear-gradient(180deg, rgba(250,250,245,0) 0%, rgba(201,176,55,0.05) 50%, rgba(250,250,245,0) 100%);
            padding: 40px 20px; position: relative; overflow: hidden;
        }
        .cover::before {
            content: ''; position: absolute; top: -100px; left: -100px;
            width: 300px; height: 300px; border-radius: 50%;
            background: radial-gradient(circle, rgba(201,176,55,0.08) 0%, transparent 70%);
        }
        .cover::after {
            content: ''; position: absolute; bottom: -100px; right: -100px;
            width: 300px; height: 300px; border-radius: 50%;
            background: radial-gradient(circle, rgba(156,175,136,0.08) 0%, transparent 70%);
        }
        .cover .invitation-label {
            font-size: 11px; letter-spacing: 4px; text-transform: uppercase;
            color: var(--gold); font-weight: 600; margin-bottom: 24px;
        }
        .cover .couple-names {
            font-size: clamp(36px, 8vw, 64px); font-weight: 800;
            background: linear-gradient(135deg, var(--gold), #8B7A1E);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            line-height: 1.2; margin-bottom: 8px;
        }
        .cover .ampersand {
            font-size: clamp(24px, 5vw, 36px); color: var(--sage);
            font-weight: 300; margin: 8px 0;
        }
        .cover .wedding-date {
            font-size: 14px; color: #888; margin-top: 24px; letter-spacing: 2px;
        }
        .cover .guest-name {
            margin-top: 40px; padding: 16px 32px;
            background: rgba(201,176,55,0.08); border: 1px solid rgba(201,176,55,0.2);
            border-radius: 16px;
        }
        .cover .guest-name span { font-size: 11px; color: #aaa; display: block; margin-bottom: 4px; }
        .cover .guest-name strong { font-size: 18px; color: var(--gold); font-weight: 700; }

        .scroll-indicator {
            position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%);
            animation: bounce 2s ease-in-out infinite;
            color: var(--gold); font-size: 20px; opacity: 0.6;
        }
        @keyframes bounce { 0%, 100% { transform: translateX(-50%) translateY(0); } 50% { transform: translateX(-50%) translateY(-10px); } }

        /* Section */
        .section { padding: 60px 20px; max-width: 640px; margin: 0 auto; }
        .section-title {
            text-align: center; margin-bottom: 40px;
        }
        .section-title h2 {
            font-size: clamp(24px, 5vw, 36px); font-weight: 700;
            color: var(--dark); margin-bottom: 8px;
        }
        .section-title .divider {
            display: flex; align-items: center; justify-content: center; gap: 12px;
        }
        .section-title .divider::before, .section-title .divider::after {
            content: ''; width: 60px; height: 1px; background: var(--gold); opacity: 0.4;
        }
        .section-title .divider i { color: var(--gold); font-size: 12px; }

        /* Countdown */
        .countdown-section {
            background: linear-gradient(135deg, rgba(201,176,55,0.06), rgba(156,175,136,0.06));
            padding: 40px 20px;
        }
        .countdown {
            display: flex; justify-content: center; gap: 16px; max-width: 400px; margin: 20px auto 0;
        }
        .countdown .unit {
            text-align: center; background: rgba(255,255,255,0.8);
            backdrop-filter: blur(10px); border: 1px solid rgba(201,176,55,0.15);
            border-radius: 16px; padding: 16px 12px; flex: 1; max-width: 80px;
        }
        .countdown .unit .num {
            font-size: 28px; font-weight: 800; color: var(--gold); line-height: 1;
        }
        .countdown .unit .lbl { font-size: 10px; color: #888; margin-top: 4px; text-transform: uppercase; letter-spacing: 1px; }

        /* Event Info */
        .event-card {
            background: rgba(255,255,255,0.7); backdrop-filter: blur(10px);
            border: 1px solid rgba(201,176,55,0.15); border-radius: 20px;
            padding: 28px; margin-bottom: 16px; text-align: center;
        }
        .event-card .icon {
            width: 48px; height: 48px; border-radius: 14px;
            background: linear-gradient(135deg, var(--gold), #8B7A1E);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px; color: white; font-size: 18px;
        }
        .event-card h4 { font-size: 16px; font-weight: 700; margin-bottom: 8px; }
        .event-card p { font-size: 13px; color: #666; line-height: 1.6; }

        /* Love Story */
        .timeline { position: relative; padding-left: 30px; }
        .timeline::before {
            content: ''; position: absolute; left: 10px; top: 0; bottom: 0;
            width: 2px; background: linear-gradient(to bottom, var(--gold), var(--sage));
            border-radius: 1px;
        }
        .timeline-item { margin-bottom: 32px; position: relative; }
        .timeline-item::before {
            content: ''; position: absolute; left: -24px; top: 4px;
            width: 10px; height: 10px; border-radius: 50%;
            background: var(--gold); border: 3px solid var(--cream);
        }
        .timeline-item .year {
            font-size: 12px; color: var(--gold); font-weight: 700;
            letter-spacing: 1px; margin-bottom: 4px;
        }
        .timeline-item h4 { font-size: 16px; font-weight: 700; margin-bottom: 4px; }
        .timeline-item p { font-size: 13px; color: #666; line-height: 1.6; }

        /* RSVP Form */
        .rsvp-section { background: linear-gradient(135deg, rgba(201,176,55,0.04), rgba(156,175,136,0.04)); }
        .rsvp-form {
            background: rgba(255,255,255,0.8); backdrop-filter: blur(16px);
            border: 1px solid rgba(201,176,55,0.15); border-radius: 24px;
            padding: 32px; max-width: 480px; margin: 0 auto;
        }
        .rsvp-btn-group { display: flex; gap: 12px; margin-bottom: 20px; }
        .rsvp-btn {
            flex: 1; padding: 14px; border-radius: 14px; border: 2px solid #E5E5E5;
            background: white; font-size: 14px; font-weight: 600;
            cursor: pointer; transition: all 0.3s; text-align: center;
        }
        .rsvp-btn.selected-hadir { border-color: var(--sage); background: rgba(156,175,136,0.1); color: #5A7042; }
        .rsvp-btn.selected-tidak { border-color: #E57373; background: rgba(229,115,115,0.1); color: #C62828; }
        .rsvp-input {
            width: 100%; padding: 14px 16px; border: 1px solid #E5E5E5;
            border-radius: 14px; font-size: 14px; background: white;
            margin-bottom: 12px; outline: none; transition: border-color 0.3s;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .rsvp-input:focus { border-color: var(--gold); }
        .rsvp-submit {
            width: 100%; padding: 16px; border: none; border-radius: 14px;
            background: linear-gradient(135deg, var(--gold), #8B7A1E);
            color: white; font-size: 15px; font-weight: 700; cursor: pointer;
            transition: all 0.3s; box-shadow: 0 4px 20px rgba(201,176,55,0.3);
        }
        .rsvp-submit:hover { box-shadow: 0 6px 30px rgba(201,176,55,0.4); transform: translateY(-1px); }

        /* Wishes */
        .wish-card {
            background: rgba(255,255,255,0.5); border: 1px solid rgba(201,176,55,0.1);
            border-radius: 16px; padding: 16px; margin-bottom: 12px;
        }
        .wish-card .name { font-size: 13px; font-weight: 700; color: var(--gold); margin-bottom: 4px; }
        .wish-card .msg { font-size: 13px; color: #666; line-height: 1.5; font-style: italic; }
        .wish-card .time { font-size: 10px; color: #bbb; margin-top: 6px; }

        /* Footer */
        .footer {
            text-align: center; padding: 40px 20px;
            background: linear-gradient(135deg, rgba(201,176,55,0.04), rgba(156,175,136,0.04));
        }
        .footer p { font-size: 12px; color: #aaa; }

        /* Success Alert */
        .success-banner {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            background: var(--sage); color: white; padding: 16px;
            text-align: center; font-size: 14px; font-weight: 600;
            animation: slideDown 0.4s ease;
        }
        @keyframes slideDown { from { transform: translateY(-100%); } to { transform: translateY(0); } }

        /* Map */
        .map-container {
            border-radius: 16px; overflow: hidden; border: 1px solid rgba(201,176,55,0.15);
            margin-top: 20px;
        }
    </style>
</head>
<body>
    @if(session('success'))
    <div class="success-banner" onclick="this.remove()">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    <!-- Cover Section -->
    <section class="cover" id="cover">
        <div class="invitation-label">The Wedding Of</div>
        <div class="couple-names serif">{{ $event->groom_name }}</div>
        <div class="ampersand serif">&</div>
        <div class="couple-names serif">{{ $event->bride_name }}</div>
        <div class="wedding-date">{{ $event->date->translatedFormat('l, d F Y') }}</div>

        <div class="guest-name">
            <span>Kepada Yth.</span>
            <strong>{{ $guest->name }}</strong>
        </div>

        <div class="scroll-indicator"><i class="fas fa-chevron-down"></i></div>
    </section>

    <!-- Countdown -->
    <section class="countdown-section">
        <div class="section-title">
            <h2 class="serif">Menghitung Hari</h2>
            <div class="divider"><i class="fas fa-heart"></i></div>
        </div>
        <div class="countdown" id="countdown">
            <div class="unit"><div class="num" id="days">0</div><div class="lbl">Hari</div></div>
            <div class="unit"><div class="num" id="hours">0</div><div class="lbl">Jam</div></div>
            <div class="unit"><div class="num" id="minutes">0</div><div class="lbl">Menit</div></div>
            <div class="unit"><div class="num" id="seconds">0</div><div class="lbl">Detik</div></div>
        </div>
    </section>

    <!-- Event Info -->
    <section class="section">
        <div class="section-title">
            <h2 class="serif">Informasi Acara</h2>
            <div class="divider"><i class="fas fa-rings-wedding"></i></div>
        </div>

        <div class="event-card">
            <div class="icon"><i class="fas fa-calendar-alt"></i></div>
            <h4>{{ $event->date->translatedFormat('l, d F Y') }}</h4>
            <p>{{ $event->time_start }} - {{ $event->time_end ?? 'Selesai' }} WIB</p>
        </div>

        <div class="event-card">
            <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
            <h4>{{ $event->venue_name }}</h4>
            <p>{{ $event->venue_address }}</p>

            @if($event->venue_lat && $event->venue_lng)
            <div class="map-container">
                <iframe
                    width="100%" height="200" style="border:0"
                    loading="lazy" allowfullscreen
                    src="https://www.google.com/maps?q={{ $event->venue_lat }},{{ $event->venue_lng }}&output=embed">
                </iframe>
            </div>
            <a href="https://www.google.com/maps/dir/?api=1&destination={{ $event->venue_lat }},{{ $event->venue_lng }}"
               target="_blank"
               style="display:inline-flex;align-items:center;gap:6px;margin-top:12px;padding:10px 20px;background:var(--gold);color:white;border-radius:12px;font-size:13px;font-weight:600;text-decoration:none;">
                <i class="fas fa-directions"></i> Buka Google Maps
            </a>
            @endif
        </div>
    </section>

    <!-- Love Story -->
    @if($event->love_story && count($event->love_story) > 0)
    <section class="section">
        <div class="section-title">
            <h2 class="serif">Love Story</h2>
            <div class="divider"><i class="fas fa-heart"></i></div>
        </div>
        <div class="timeline">
            @foreach($event->love_story as $story)
            <div class="timeline-item">
                <div class="year">{{ $story['year'] }}</div>
                <h4>{{ $story['title'] }}</h4>
                <p>{{ $story['description'] }}</p>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- RSVP -->
    <section class="section rsvp-section" id="rsvp">
        <div class="section-title">
            <h2 class="serif">Konfirmasi Kehadiran</h2>
            <div class="divider"><i class="fas fa-envelope-open-text"></i></div>
        </div>

        @if($guest->rsvp)
        <div class="rsvp-form" style="text-align:center;">
            <i class="fas fa-check-circle" style="font-size:48px;color:var(--sage);margin-bottom:12px;"></i>
            <h4 style="font-size:18px;font-weight:700;margin-bottom:8px;">Terima Kasih!</h4>
            <p style="font-size:14px;color:#666;margin-bottom:16px;">
                Anda telah mengkonfirmasi <strong>{{ $guest->rsvp->status === 'hadir' ? 'kehadiran' : 'ketidakhadiran' }}</strong>
                @if($guest->rsvp->status === 'hadir') dengan {{ $guest->rsvp->number_of_guests }} orang @endif
            </p>
            @if($guest->rsvp->status === 'hadir')
            <a href="{{ route('invitation.ticket', $guest->slug) }}"
               style="display:inline-flex;align-items:center;gap:8px;padding:14px 28px;background:linear-gradient(135deg,var(--gold),#8B7A1E);color:white;border-radius:14px;font-weight:700;font-size:14px;text-decoration:none;box-shadow:0 4px 20px rgba(201,176,55,0.3);">
                <i class="fas fa-ticket-alt"></i> Lihat Tiket Digital
            </a>
            @endif
        </div>
        @else
        <form action="{{ route('invitation.rsvp', $guest->slug) }}" method="POST" class="rsvp-form">
            @csrf
            <input type="hidden" name="status" id="rsvp-status" value="">

            <div class="rsvp-btn-group">
                <button type="button" class="rsvp-btn" onclick="selectRsvp('hadir', this)">
                    <i class="fas fa-check-circle"></i><br>Hadir
                </button>
                <button type="button" class="rsvp-btn" onclick="selectRsvp('tidak_hadir', this)">
                    <i class="fas fa-times-circle"></i><br>Tidak Hadir
                </button>
            </div>

            <div id="guest-count-wrapper" style="display:none;">
                <label style="font-size:13px;font-weight:600;color:#666;margin-bottom:6px;display:block;">Jumlah Tamu (maks {{ $guest->max_companions }})</label>
                <input type="number" name="number_of_guests" class="rsvp-input" min="1" max="{{ $guest->max_companions }}" value="1">
            </div>

            <textarea name="message" class="rsvp-input" rows="3" placeholder="Tulis ucapan untuk pengantin..." style="resize:none;"></textarea>

            <button type="submit" class="rsvp-submit" id="rsvp-submit-btn" disabled style="opacity:0.5;">
                <i class="fas fa-paper-plane"></i> Kirim Konfirmasi
            </button>
        </form>
        @endif
    </section>

    <!-- Wishes -->
    @if($wishes->count() > 0)
    <section class="section">
        <div class="section-title">
            <h2 class="serif">Buku Ucapan</h2>
            <div class="divider"><i class="fas fa-book-open"></i></div>
        </div>
        <div style="max-height:400px;overflow-y:auto;padding-right:8px;">
            @foreach($wishes as $wish)
            <div class="wish-card">
                <div class="name">{{ $wish->name }}</div>
                <div class="msg">"{{ $wish->message }}"</div>
                <div class="time">{{ $wish->created_at->diffForHumans() }}</div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Footer -->
    <footer class="footer">
        <p class="serif" style="font-size:18px;color:var(--gold);font-weight:600;margin-bottom:8px;">
            {{ $event->groom_name }} & {{ $event->bride_name }}
        </p>
        <p>{{ $event->date->translatedFormat('d F Y') }}</p>
        <p style="margin-top:16px;">&copy; Wedding Guest Management System</p>
    </footer>

    <script>
        // Countdown
        const weddingDate = new Date('{{ $event->date->format("Y-m-d") }}T{{ $event->time_start }}:00').getTime();
        function updateCountdown() {
            const now = new Date().getTime();
            const diff = weddingDate - now;
            if (diff <= 0) {
                document.getElementById('countdown').innerHTML = '<p style="text-align:center;color:var(--gold);font-size:18px;font-weight:700;">Hari Bahagia Telah Tiba! 🎉</p>';
                return;
            }
            document.getElementById('days').textContent = Math.floor(diff / 86400000);
            document.getElementById('hours').textContent = Math.floor((diff % 86400000) / 3600000);
            document.getElementById('minutes').textContent = Math.floor((diff % 3600000) / 60000);
            document.getElementById('seconds').textContent = Math.floor((diff % 60000) / 1000);
        }
        setInterval(updateCountdown, 1000);
        updateCountdown();

        // RSVP form
        function selectRsvp(status, btn) {
            document.getElementById('rsvp-status').value = status;
            document.querySelectorAll('.rsvp-btn').forEach(b => { b.className = 'rsvp-btn'; });
            btn.classList.add(status === 'hadir' ? 'selected-hadir' : 'selected-tidak');

            const countWrapper = document.getElementById('guest-count-wrapper');
            countWrapper.style.display = status === 'hadir' ? 'block' : 'none';

            const submitBtn = document.getElementById('rsvp-submit-btn');
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
        }
    </script>
</body>
</html>
