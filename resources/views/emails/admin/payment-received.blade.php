<x-mail::message>
# ✅ Pembayaran Diterima

Pembayaran telah dikonfirmasi untuk pesanan berikut.

## Detail

| | |
|:---|:---|
| **No. Pesanan** | {{ $order->order_number }} |
| **Pelanggan** | {{ $order->customer_name }} |
| **Paket** | {{ $order->package_name }} |
| **Total Dibayar** | **{{ $order->formatted_total_amount }}** |
| **Dibayar Pada** | {{ $order->paid_at?->format('d F Y H:i') ?? '-' }} |

<x-mail::button :url="url('/admin/orders/' . $order->id)">
Lihat Detail
</x-mail::button>

{{ config('app.name') }}
</x-mail::message>
