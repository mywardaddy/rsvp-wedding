<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tiket Digital - {{ $guest->name }}</title>
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800|playfair-display:600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        :root { --gold: #C9B037; --sage: #9CAF88; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #FAFAF5, #FFF8E7);
            min-height: 100vh; display: flex; align-items: center;
            justify-content: center; padding: 20px;
        }
        .serif { font-family: 'Playfair Display', serif; }

        .ticket-container { max-width: 400px; width: 100%; }

        .ticket {
            background: white; border-radius: 24px;
            overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            position: relative;
        }

        .ticket-header {
            background: linear-gradient(135deg, var(--gold), #8B7A1E);
            padding: 24px; text-align: center; color: white;
        }
        .ticket-header .label {
            font-size: 10px; letter-spacing: 3px; text-transform: uppercase;
            opacity: 0.8; margin-bottom: 8px;
        }
        .ticket-header h2 {
            font-size: 20px; font-weight: 800; margin-bottom: 4px;
        }
        .ticket-header .couple {
            font-size: 14px; font-weight: 500; opacity: 0.9;
        }

        .ticket-body { padding: 24px; text-align: center; }

        .qr-wrapper {
            background: #FAFAF5; border-radius: 20px; padding: 24px;
            display: inline-block; margin: 0 auto 20px; box-shadow: inset 0 2px 8px rgba(0,0,0,0.04);
        }
        .qr-wrapper svg { max-width: 200px; height: auto; }

        .guest-name {
            font-size: 22px; font-weight: 800; color: #2C2C2C; margin-bottom: 4px;
        }
        .guest-id {
            font-size: 12px; font-family: monospace; color: #aaa; margin-bottom: 16px;
            letter-spacing: 2px;
        }

        .ticket-divider {
            display: flex; align-items: center; margin: 20px 0; position: relative;
        }
        .ticket-divider::before {
            content: ''; flex: 1; height: 1px;
            background: repeating-linear-gradient(90deg, #E5E5E5 0, #E5E5E5 6px, transparent 6px, transparent 12px);
        }
        .ticket-divider .notch-left, .ticket-divider .notch-right {
            position: absolute; width: 20px; height: 20px;
            background: linear-gradient(135deg, #FAFAF5, #FFF8E7);
            border-radius: 50%;
        }
        .ticket-divider .notch-left { left: -10px; }
        .ticket-divider .notch-right { right: -10px; }

        .ticket-details { padding: 0 24px 24px; }
        .detail-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 12px;
        }
        .detail-item {
            text-align: left; padding: 12px; background: #FAFAF5;
            border-radius: 12px;
        }
        .detail-item .label {
            font-size: 10px; color: #aaa; text-transform: uppercase;
            letter-spacing: 1px; margin-bottom: 2px;
        }
        .detail-item .value { font-size: 13px; font-weight: 600; color: #2C2C2C; }

        .detail-item.full { grid-column: 1 / -1; }

        /* Actions */
        .actions {
            padding: 0 24px 24px; display: flex; flex-direction: column; gap: 8px;
        }
        .action-btn {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            padding: 14px; border-radius: 14px; font-size: 14px; font-weight: 600;
            text-decoration: none; cursor: pointer; border: none; transition: all 0.3s;
        }
        .action-btn.primary {
            background: linear-gradient(135deg, var(--gold), #8B7A1E);
            color: white; box-shadow: 0 4px 20px rgba(201,176,55,0.3);
        }
        .action-btn.primary:hover { box-shadow: 0 6px 30px rgba(201,176,55,0.4); }
        .action-btn.secondary {
            background: rgba(201,176,55,0.08); color: #8B7A1E;
            border: 1px solid rgba(201,176,55,0.2);
        }
        .action-btn.sage {
            background: rgba(156,175,136,0.1); color: #5A7042;
            border: 1px solid rgba(156,175,136,0.2);
        }

        .back-link {
            text-align: center; margin-top: 16px;
        }
        .back-link a {
            font-size: 13px; color: #aaa; text-decoration: none;
        }
        .back-link a:hover { color: var(--gold); }

        /* Category badge */
        .category-badge {
            display: inline-block; padding: 4px 12px; border-radius: 20px;
            font-size: 11px; font-weight: 700; margin-bottom: 16px;
        }
        .cat-vip { background: linear-gradient(135deg, rgba(201,176,55,0.2), rgba(201,176,55,0.1)); color: #8B7A1E; border: 1px solid rgba(201,176,55,0.3); }
        .cat-keluarga { background: rgba(156,175,136,0.15); color: #5A7042; }
        .cat-sahabat { background: rgba(107,142,155,0.15); color: #4A7585; }
        .cat-reguler { background: rgba(158,158,158,0.15); color: #616161; }
    </style>
</head>
<body>
    <div class="ticket-container">
        <div class="ticket" id="ticket">
            <!-- Header -->
            <div class="ticket-header">
                <div class="label">Wedding Invitation Ticket</div>
                <h2 class="serif">{{ $event->groom_name }} & {{ $event->bride_name }}</h2>
                <div class="couple">{{ $event->date->translatedFormat('d F Y') }}</div>
            </div>

            <!-- QR Code -->
            <div class="ticket-body">
                <div class="qr-wrapper">{!! $qrSvg !!}</div>

                <div class="guest-name">{{ $guest->name }}</div>
                <div class="guest-id">{{ $guest->qr_code }}</div>

                <span class="category-badge cat-{{ $guest->category }}">
                    {{ $guest->category_label }}
                </span>
            </div>

            <!-- Divider with notches -->
            <div class="ticket-divider">
                <div class="notch-left"></div>
                <div class="notch-right"></div>
            </div>

            <!-- Details -->
            <div class="ticket-details">
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="label"><i class="fas fa-calendar-alt"></i> Tanggal</div>
                        <div class="value">{{ $event->date->translatedFormat('d M Y') }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="label"><i class="fas fa-clock"></i> Waktu</div>
                        <div class="value">{{ $event->time_start }} WIB</div>
                    </div>
                    <div class="detail-item full">
                        <div class="label"><i class="fas fa-map-marker-alt"></i> Lokasi</div>
                        <div class="value">{{ $event->venue_name }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="label"><i class="fas fa-users"></i> Jumlah</div>
                        <div class="value">{{ $guest->rsvp->number_of_guests ?? 1 }} Orang</div>
                    </div>
                    <div class="detail-item">
                        <div class="label"><i class="fas fa-hashtag"></i> Nomor</div>
                        <div class="value">#{{ str_pad($guest->id, 4, '0', STR_PAD_LEFT) }}</div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="actions">
                <a href="https://calendar.google.com/calendar/render?action=TEMPLATE&text={{ urlencode($event->title) }}&dates={{ $event->date->format('Ymd') }}T{{ str_replace(':', '', $event->time_start) }}00/{{ $event->date->format('Ymd') }}T{{ str_replace(':', '', $event->time_end ?? '23:59') }}00&location={{ urlencode($event->venue_name . ', ' . $event->venue_address) }}&details={{ urlencode($event->description ?? '') }}"
                   target="_blank" class="action-btn sage">
                    <i class="fas fa-calendar-plus"></i> Tambahkan ke Google Calendar
                </a>
            </div>
        </div>

        <div class="back-link">
            <a href="{{ route('invitation.show', $guest->slug) }}"><i class="fas fa-arrow-left"></i> Kembali ke Undangan</a>
        </div>
    </div>
</body>
</html>
