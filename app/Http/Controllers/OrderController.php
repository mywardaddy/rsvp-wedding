<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\PricingPackage;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected PaymentService $paymentService,
    ) {}

    /**
     * Show order form pre-filled with selected package.
     */
    public function create(string $packageSlug)
    {
        $package = PricingPackage::where('slug', $packageSlug)
            ->active()
            ->with('features')
            ->firstOrFail();

        return view('orders.create', compact('package'));
    }

    /**
     * Process order form submission.
     */
    public function store(StoreOrderRequest $request)
    {
        $order = $this->orderService->createOrder($request->validated());

        return redirect()->route('order.show', $order->order_number)
            ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
    }

    /**
     * Show order detail & payment status.
     */
    public function show(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['pricingPackage.features', 'payments'])
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }

    /**
     * Show payment page with method selection.
     */
    public function payment(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['pricingPackage', 'payments'])
            ->firstOrFail();

        if ($order->isPaid()) {
            return redirect()->route('order.show', $order->order_number)
                ->with('info', 'Pesanan ini sudah dibayar.');
        }

        $paymentMethods = $this->getPaymentMethods();

        return view('orders.payment', compact('order', 'paymentMethods'));
    }

    /**
     * Process payment method selection.
     */
    public function processPayment(Request $request, string $orderNumber)
    {
        $request->validate([
            'payment_method' => ['required', 'string'],
        ]);

        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        if ($order->isPaid()) {
            return redirect()->route('order.show', $order->order_number)
                ->with('info', 'Pesanan ini sudah dibayar.');
        }

        $payment = $this->paymentService->createPayment(
            $order,
            $request->payment_method,
            config('services.payment.gateway', 'manual')
        );

        // If gateway returns a redirect URL, redirect to it
        if ($payment->payment_url) {
            return redirect()->away($payment->payment_url);
        }

        // Otherwise show payment details (VA number, QRIS, etc.)
        return redirect()->route('order.show', $order->order_number)
            ->with('success', 'Pembayaran sedang diproses. Silakan lakukan pembayaran sesuai instruksi.');
    }

    /**
     * Available payment methods.
     */
    protected function getPaymentMethods(): array
    {
        return [
            'qris' => [
                'name' => 'QRIS',
                'icon' => 'fa-qrcode',
                'category' => 'QRIS',
                'description' => 'Scan QR untuk bayar dari semua e-wallet & mobile banking',
            ],
            'gopay' => [
                'name' => 'GoPay',
                'icon' => 'fa-wallet',
                'category' => 'E-Wallet',
                'description' => 'Bayar via GoPay',
            ],
            'ovo' => [
                'name' => 'OVO',
                'icon' => 'fa-wallet',
                'category' => 'E-Wallet',
                'description' => 'Bayar via OVO',
            ],
            'dana' => [
                'name' => 'DANA',
                'icon' => 'fa-wallet',
                'category' => 'E-Wallet',
                'description' => 'Bayar via DANA',
            ],
            'shopeepay' => [
                'name' => 'ShopeePay',
                'icon' => 'fa-wallet',
                'category' => 'E-Wallet',
                'description' => 'Bayar via ShopeePay',
            ],
            'va_bca' => [
                'name' => 'BCA Virtual Account',
                'icon' => 'fa-building-columns',
                'category' => 'Virtual Account',
                'description' => 'Transfer via Virtual Account BCA',
            ],
            'va_bni' => [
                'name' => 'BNI Virtual Account',
                'icon' => 'fa-building-columns',
                'category' => 'Virtual Account',
                'description' => 'Transfer via Virtual Account BNI',
            ],
            'va_bri' => [
                'name' => 'BRI Virtual Account',
                'icon' => 'fa-building-columns',
                'category' => 'Virtual Account',
                'description' => 'Transfer via Virtual Account BRI',
            ],
            'va_mandiri' => [
                'name' => 'Mandiri Virtual Account',
                'icon' => 'fa-building-columns',
                'category' => 'Virtual Account',
                'description' => 'Transfer via Virtual Account Mandiri',
            ],
            'va_cimb' => [
                'name' => 'CIMB Virtual Account',
                'icon' => 'fa-building-columns',
                'category' => 'Virtual Account',
                'description' => 'Transfer via Virtual Account CIMB Niaga',
            ],
            'va_permata' => [
                'name' => 'Permata Virtual Account',
                'icon' => 'fa-building-columns',
                'category' => 'Virtual Account',
                'description' => 'Transfer via Virtual Account Permata',
            ],
            'credit_card' => [
                'name' => 'Kartu Kredit / Debit',
                'icon' => 'fa-credit-card',
                'category' => 'Kartu',
                'description' => 'Visa, Mastercard, JCB',
            ],
        ];
    }
}
