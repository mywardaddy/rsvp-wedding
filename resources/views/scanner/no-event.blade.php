<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scanner - Tidak Ada Event</title>
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,600,700&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #0F0F0F; color: #E5E5E5; min-height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; }
        .container { text-align: center; padding: 40px; }
        .icon { font-size: 64px; margin-bottom: 16px; }
        h2 { font-size: 20px; margin-bottom: 8px; color: #C9B037; }
        p { font-size: 14px; color: #888; margin-bottom: 24px; }
        a { color: #C9B037; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">📱</div>
        <h2>Belum Ada Event</h2>
        <p>Anda belum ditugaskan ke event manapun.<br>Hubungi pengantin atau admin untuk aktivasi.</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="background:#C9B037;color:#0F0F0F;border:none;padding:12px 24px;border-radius:12px;font-weight:700;cursor:pointer;">Logout</button>
        </form>
    </div>
</body>
</html>
