<x-mail::message>
# Akun Anda Telah Dibuat! 🎉

Halo **{{ $order->groom_name }}** & **{{ $order->bride_name }}**,

Terima kasih telah mempercayakan **{{ config('app.name', 'NIKAH YUK!') }}** untuk mengelola acara pernikahan Anda.

Pembayaran Anda telah berhasil dikonfirmasi dan akun Anda telah dibuat secara otomatis.

---

## Informasi Login

| | |
|:---|:---|
| **Nama Pasangan** | {{ $order->groom_name }} & {{ $order->bride_name }} |
| **Email** | {{ $user->email }} |
| **Password** | `{{ $plainPassword }}` |
| **Paket** | {{ $order->package_name }} |
| **No. Pesanan** | {{ $order->order_number }} |

> **Penting:** Segera ubah password Anda setelah login pertama kali demi keamanan akun.

<x-mail::button :url="$loginUrl">
Login Sekarang
</x-mail::button>

Setelah login, Anda dapat langsung mengelola daftar tamu, undangan digital, dan seluruh kebutuhan acara pernikahan Anda melalui Dashboard.

Jika ada pertanyaan, jangan ragu untuk menghubungi tim kami.

Salam hangat,<br>
{{ config('app.name') }}
</x-mail::message>
