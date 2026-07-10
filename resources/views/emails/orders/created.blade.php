<x-mail::message>
# Pesanan Anda Telah Dibuat

Terima kasih, **{{ $order->customer_name }}**! Pesanan Anda telah berhasil dibuat.

## Detail Pesanan

| | |
|:---|:---|
| **No. Pesanan** | {{ $order->order_number }} |
| **Paket** | {{ $order->package_name }} |
| **Pengantin** | {{ $order->groom_name }} & {{ $order->bride_name }} |
| **Tanggal Pernikahan** | {{ $order->wedding_date->format('d F Y') }} |
@if($order->discount_amount > 0)
| **Harga Asli** | {{ $order->formatted_original_price }} |
| **Diskon** | -{{ $order->formatted_discount_amount }} |
@endif
| **Total Pembayaran** | **{{ $order->formatted_total_amount }}** |

## Status: Menunggu Pembayaran

Silakan lakukan pembayaran untuk mengaktifkan paket undangan digital Anda.

<x-mail::button :url="url('/order/' . $order->order_number)">
Lihat Pesanan & Bayar
</x-mail::button>

Salam hangat,<br>
{{ config('app.name') }}
</x-mail::message>
