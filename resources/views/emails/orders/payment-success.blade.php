<x-mail::message>
# Pembayaran Berhasil! 🎉

Selamat, **{{ $order->customer_name }}**! Pembayaran Anda telah dikonfirmasi.

## Detail Pesanan

| | |
|:---|:---|
| **No. Pesanan** | {{ $order->order_number }} |
| **Paket** | {{ $order->package_name }} |
| **Pengantin** | {{ $order->groom_name }} & {{ $order->bride_name }} |
| **Total Dibayar** | **{{ $order->formatted_total_amount }}** |
| **Status** | ✅ Lunas |

Paket undangan digital Anda telah aktif. Tim kami akan segera menghubungi Anda melalui WhatsApp untuk langkah selanjutnya.

<x-mail::button :url="url('/order/' . $order->order_number)">
Lihat Detail Pesanan
</x-mail::button>

Salam hangat,<br>
{{ config('app.name') }}
</x-mail::message>
