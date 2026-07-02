<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Scanner - {{ $event->title }}</title>
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #0F0F0F;
            color: #E5E5E5;
            min-height: 100vh;
            overflow-x: hidden;
        }
        .scanner-header {
            background: rgba(15, 15, 15, 0.9);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(201, 176, 55, 0.15);
            padding: 12px 16px;
            position: fixed; top: 0; left: 0; right: 0; z-index: 50;
        }
        .scanner-header h1 { font-size: 16px; font-weight: 700; color: #C9B037; }
        .scanner-header .stats {
            display: flex; gap: 16px; margin-top: 4px;
        }
        .scanner-header .stats span {
            font-size: 11px; color: #888;
        }
        .scanner-header .stats .count {
            color: #C9B037; font-weight: 700; font-size: 13px;
        }

        .scanner-body { padding-top: 110px; padding-bottom: 80px; }

        /* Camera Container */
        #reader-container {
            width: 100%; max-width: 400px; margin: 0 auto;
            padding: 16px;
        }
        #reader {
            width: 100% !important;
            border-radius: 16px;
            overflow: hidden;
            border: 2px solid rgba(201, 176, 55, 0.3);
        }
        #reader video { border-radius: 14px; }

        /* Result Overlay */
        .scan-result {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            display: flex; align-items: center; justify-content: center;
            z-index: 100; padding: 20px;
            transition: all 0.3s ease;
        }
        .scan-result.hidden { display: none; }
        .scan-result.valid { background: rgba(76, 175, 80, 0.95); }
        .scan-result.invalid { background: rgba(244, 67, 54, 0.95); }

        .result-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 32px;
            text-align: center;
            max-width: 360px;
            width: 100%;
            animation: resultPop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        @keyframes resultPop {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        .result-icon { font-size: 64px; margin-bottom: 16px; }
        .result-name { font-size: 24px; font-weight: 800; color: white; margin-bottom: 4px; }
        .result-detail { font-size: 14px; color: rgba(255,255,255,0.85); margin-bottom: 4px; }
        .result-message { font-size: 20px; font-weight: 700; color: white; margin-top: 16px; }
        .result-dismiss {
            margin-top: 24px; padding: 12px 32px;
            background: rgba(255,255,255,0.2); border: none; border-radius: 12px;
            color: white; font-size: 14px; font-weight: 600; cursor: pointer;
            transition: background 0.2s;
        }
        .result-dismiss:hover { background: rgba(255,255,255,0.3); }

        /* Manual Search */
        .manual-section { padding: 16px; max-width: 400px; margin: 0 auto; }
        .search-input {
            width: 100%; padding: 14px 16px; padding-left: 44px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(201, 176, 55, 0.2);
            border-radius: 14px; color: #E5E5E5; font-size: 14px;
            outline: none; transition: all 0.3s;
        }
        .search-input:focus {
            border-color: rgba(201, 176, 55, 0.5);
            background: rgba(255,255,255,0.1);
        }
        .search-icon {
            position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
            color: #666; font-size: 14px;
        }

        /* Guest Items */
        .guest-item {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 14px; padding: 14px 16px;
            margin-top: 8px; display: flex; align-items: center;
            justify-content: space-between; transition: all 0.2s;
        }
        .guest-item:hover { background: rgba(201, 176, 55, 0.08); }
        .guest-item .name { font-weight: 600; font-size: 14px; }
        .guest-item .meta { font-size: 11px; color: #888; margin-top: 2px; }
        .checkin-btn {
            padding: 8px 16px; border-radius: 10px;
            font-size: 12px; font-weight: 700; border: none; cursor: pointer;
            transition: all 0.2s;
        }
        .checkin-btn.available { background: #C9B037; color: #0F0F0F; }
        .checkin-btn.available:hover { background: #D4BC45; }
        .checkin-btn.done { background: rgba(76,175,80,0.15); color: #66BB6A; cursor: default; }

        /* Recent Scans */
        .recent-scans { padding: 16px; max-width: 400px; margin: 0 auto; }
        .recent-scans h3 { font-size: 13px; font-weight: 700; color: #888; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; }
        .scan-log {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.04);
        }
        .scan-log .dot { width: 8px; height: 8px; border-radius: 50%; background: #66BB6A; flex-shrink: 0; }
        .scan-log .info { flex: 1; }
        .scan-log .info .n { font-size: 13px; font-weight: 600; }
        .scan-log .info .t { font-size: 11px; color: #666; }

        /* Tabs */
        .tab-bar {
            position: fixed; bottom: 0; left: 0; right: 0;
            background: rgba(15, 15, 15, 0.95); backdrop-filter: blur(20px);
            border-top: 1px solid rgba(201, 176, 55, 0.15);
            display: flex; z-index: 50;
        }
        .tab {
            flex: 1; padding: 12px; text-align: center;
            color: #666; font-size: 11px; font-weight: 600;
            cursor: pointer; transition: color 0.2s;
            border: none; background: none;
        }
        .tab.active { color: #C9B037; }
        .tab i { display: block; font-size: 18px; margin-bottom: 4px; }

        .tab-content { display: none; }
        .tab-content.active { display: block; }

        /* Logout link */
        .logout-link { color: #888; font-size: 12px; text-decoration: none; }
        .logout-link:hover { color: #E57373; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="scanner-header">
        <div style="display:flex; justify-content:space-between; align-items:flex-start;">
            <div>
                <h1><i class="fas fa-qrcode"></i> Scanner</h1>
                <div style="margin-top:4px;">
                    <span style="font-size:13px; font-weight:700; color:#E5E5E5;">{{ $event->groom_name }} & {{ $event->bride_name }}</span>
                </div>
                <div class="stats">
                    <span>Check-in: <span class="count" id="checkin-count">{{ $checkedIn }}</span>/{{ $totalGuests }}</span>
                    <span>Hari ini: <span class="count">{{ $checkedInToday }}</span></span>
                </div>
                <div style="margin-top:2px; font-size:10px; color:#666;">
                    <i class="fas fa-calendar-alt" style="margin-right:3px;"></i> {{ $event->date->format('d M Y') }}
                    <span style="margin:0 4px;">·</span>
                    <i class="fas fa-map-marker-alt" style="margin-right:3px;"></i> {{ $event->venue_name }}
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-link" style="border:none;background:none;cursor:pointer;"><i class="fas fa-sign-out-alt"></i></button>
            </form>
        </div>
    </div>

    <!-- Scan Result Overlay -->
    <div id="scan-result" class="scan-result hidden">
        <div class="result-card">
            <div class="result-icon" id="result-icon">🟢</div>
            <div class="result-name" id="result-name"></div>
            <div class="result-detail" id="result-category"></div>
            <div class="result-detail" id="result-companions"></div>
            <div class="result-message" id="result-message"></div>
            <button class="result-dismiss" onclick="dismissResult()">Tutup</button>
        </div>
    </div>

    <div class="scanner-body">
        <!-- Camera Tab -->
        <div id="tab-camera" class="tab-content active">
            <div id="reader-container">
                <div id="reader"></div>
            </div>
        </div>

        <!-- Manual Search Tab -->
        <div id="tab-search" class="tab-content">
            <div class="manual-section">
                <div style="position:relative; margin-bottom: 12px;">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" id="manual-search" placeholder="Cari nama, telepon, atau QR code...">
                </div>
                <div id="search-results"></div>
            </div>
        </div>

        <!-- Recent Tab -->
        <div id="tab-recent" class="tab-content">
            <div class="recent-scans">
                <h3><i class="fas fa-history"></i> Scan Terbaru</h3>
                @forelse($recentScans as $scan)
                <div class="scan-log">
                    <div class="dot"></div>
                    <div class="info">
                        <div class="n">{{ $scan->guest->name }}</div>
                        <div class="t">{{ $scan->checked_in_at->format('H:i') }} · {{ $scan->method }}</div>
                    </div>
                </div>
                @empty
                <p style="text-align:center; color:#666; padding:40px 0; font-size:13px;">Belum ada scan</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Tab Bar -->
    <div class="tab-bar">
        <button class="tab active" onclick="switchTab('camera')">
            <i class="fas fa-camera"></i> Scan
        </button>
        <button class="tab" onclick="switchTab('search')">
            <i class="fas fa-search"></i> Cari
        </button>
        <button class="tab" onclick="switchTab('recent')">
            <i class="fas fa-history"></i> Riwayat
        </button>
    </div>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        let html5QrCode;

        // Initialize QR Scanner
        function initScanner() {
            html5QrCode = new Html5Qrcode("reader");
            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 250, height: 250 } },
                onScanSuccess,
                () => {}
            ).catch(err => {
                document.getElementById('reader').innerHTML =
                    '<div style="padding:40px;text-align:center;color:#888;"><i class="fas fa-camera" style="font-size:48px;margin-bottom:12px;display:block;"></i>Izinkan akses kamera untuk memulai scan</div>';
            });
        }

        let isScanning = true;

        function onScanSuccess(decodedText) {
            if (!isScanning) return;
            isScanning = false;

            // Vibration feedback
            if (navigator.vibrate) navigator.vibrate(200);

            fetch('{{ route("scanner.scan") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ qr_code: decodedText })
            })
            .then(r => r.json().then(data => ({ ok: r.ok, data })))
            .then(({ ok, data }) => {
                showResult(data);
            })
            .catch(err => {
                showResult({ valid: false, message: 'Terjadi kesalahan!', icon: '🔴' });
            });
        }

        function showResult(data) {
            const overlay = document.getElementById('scan-result');
            overlay.className = 'scan-result ' + (data.valid ? 'valid' : 'invalid');

            document.getElementById('result-icon').textContent = data.icon || (data.valid ? '🟢' : '🔴');
            document.getElementById('result-name').textContent = data.guest?.name || '';
            document.getElementById('result-category').textContent = data.guest?.category ? `${data.guest.category} · ${data.guest.group || ''}` : '';
            document.getElementById('result-companions').textContent = data.guest?.companions ? `${data.guest.companions} orang` : '';
            document.getElementById('result-message').textContent = data.message;

            if (data.valid) {
                const count = document.getElementById('checkin-count');
                count.textContent = parseInt(count.textContent) + 1;
            }

            // Auto dismiss after 3 seconds
            setTimeout(dismissResult, 3000);
        }

        function dismissResult() {
            document.getElementById('scan-result').className = 'scan-result hidden';
            isScanning = true;
        }

        // Tab switching
        function switchTab(tab) {
            document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.getElementById('tab-' + tab).classList.add('active');
            event.currentTarget.classList.add('active');

            if (tab === 'camera' && !html5QrCode) initScanner();
        }

        // Manual search
        let searchTimeout;
        document.getElementById('manual-search').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const q = this.value.trim();
            if (q.length < 2) { document.getElementById('search-results').innerHTML = ''; return; }

            searchTimeout = setTimeout(() => {
                fetch(`{{ route('scanner.search') }}?q=${encodeURIComponent(q)}`, {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                })
                .then(r => r.json())
                .then(guests => {
                    const html = guests.map(g => `
                        <div class="guest-item">
                            <div>
                                <div class="name">${g.name}</div>
                                <div class="meta">${g.category} · ${g.phone || '-'}</div>
                            </div>
                            ${g.is_checked_in
                                ? '<span class="checkin-btn done"><i class="fas fa-check"></i> Sudah</span>'
                                : `<button class="checkin-btn available" onclick="manualCheckin(${g.id})">Check-in</button>`
                            }
                        </div>
                    `).join('');
                    document.getElementById('search-results').innerHTML = html || '<p style="text-align:center;color:#666;padding:20px;font-size:13px;">Tidak ditemukan</p>';
                });
            }, 300);
        });

        function manualCheckin(guestId) {
            fetch('{{ route("scanner.manual-checkin") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ guest_id: guestId })
            })
            .then(r => r.json().then(data => ({ ok: r.ok, data })))
            .then(({ ok, data }) => {
                if (data.valid) {
                    showResult(data);
                    const count = document.getElementById('checkin-count');
                    count.textContent = parseInt(count.textContent) + 1;
                } else {
                    alert(data.message);
                }
            });
        }

        // Init scanner on load
        initScanner();
    </script>
</body>
</html>
