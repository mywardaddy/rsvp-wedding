<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentCallbackController extends Controller
{
    public function __construct(protected PaymentService $paymentService) {}

    /**
     * Handle Midtrans notification webhook.
     */
    public function handleMidtrans(Request $request)
    {
        $payload = $request->all();

        // TODO: Verify signature from Midtrans
        // $serverKey = config('services.midtrans.server_key');
        // $signature = hash('sha512', $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . $serverKey);

        $payment = $this->paymentService->handleCallback('midtrans', $payload);

        return response()->json(['status' => 'ok']);
    }

    /**
     * Handle Xendit notification webhook.
     */
    public function handleXendit(Request $request)
    {
        $payload = $request->all();

        // TODO: Verify webhook token from Xendit
        // $webhookToken = config('services.xendit.webhook_token');
        // Verify $request->header('x-callback-token') === $webhookToken

        $payment = $this->paymentService->handleCallback('xendit', $payload);

        return response()->json(['status' => 'ok']);
    }
}
