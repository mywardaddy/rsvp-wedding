<x-mail::message>
# 📋 Pesanan Baru Masuk

Ada pesanan baru yang perlu diperhatikan.

## Detail Pesanan

| | |
|:---|:---|
| **No. Pesanan** | {{ $order->order_number }} |
| **Pelanggan** | {{ $order->customer_name }} |
| **Email** | {{ $order->customer_email }} |
| **WhatsApp** | {{ $order->customer_whatsapp }} |
| **Paket** | {{ $order->package_name }} |
| **Pengantin** | {{ $order->groom_name }} & {{ $order->bride_name }} |
| **Tanggal Nikah** | {{ $order->wedding_date->format('d F Y') }} |
| **Total** | **{{ $order->formatted_total_amount }}** |
| **Status** | Menunggu Pembayaran |

<x-mail::button :url="url('/admin/orders/' . $order->id)">
Lihat di Admin Panel
</x-mail::button>

{{ config('app.name') }}
</x-mail::message>
