<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentTransaction;

class PaymentService
{
    /**
     * Create a payment record for an order.
     * Gateway-agnostic: ready for Midtrans/Xendit integration.
     */
    public function createPayment(Order $order, string $paymentMethod, string $gateway = 'manual'): Payment
    {
        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_gateway' => $gateway,
            'payment_method' => $paymentMethod,
            'amount' => $order->total_amount,
            'status' => 'pending',
            'expired_at' => now()->addHours(24),
        ]);

        // TODO: Integrate with actual payment gateway
        // For Midtrans: Create snap token and redirect URL
        // For Xendit: Create invoice and get payment URL
        // $this->createGatewayTransaction($payment);

        return $payment;
    }

    /**
     * Handle payment callback/webhook from gateway.
     * Stub method — implement per gateway.
     */
    public function handleCallback(string $gateway, array $payload): ?Payment
    {
        // Log the transaction
        $transactionId = $payload['transaction_id'] ?? $payload['id'] ?? null;

        if (!$transactionId) {
            return null;
        }

        $payment = Payment::where('gateway_transaction_id', $transactionId)->first();

        if (!$payment) {
            return null;
        }

        // Record transaction log
        PaymentTransaction::create([
            'payment_id' => $payment->id,
            'transaction_type' => 'webhook',
            'payload' => $payload,
            'status' => $payload['status'] ?? 'unknown',
        ]);

        // Update payment status based on gateway response
        $status = $this->mapGatewayStatus($gateway, $payload);
        $payment->update([
            'status' => $status,
            'gateway_response' => $payload,
            'paid_at' => $status === 'success' ? now() : null,
        ]);

        // If payment successful, update order
        if ($status === 'success') {
            $orderService = new OrderService();
            $orderService->markAsPaid($payment->order);
        }

        return $payment;
    }

    /**
     * Mark payment as manually confirmed (for manual transfer).
     */
    public function confirmManualPayment(Payment $payment): Payment
    {
        $payment->update([
            'status' => 'success',
            'paid_at' => now(),
        ]);

        PaymentTransaction::create([
            'payment_id' => $payment->id,
            'transaction_type' => 'manual_confirmation',
            'payload' => ['confirmed_by' => auth()->id(), 'confirmed_at' => now()->toISOString()],
            'status' => 'success',
        ]);

        // Update order status
        $orderService = new OrderService();
        $orderService->markAsPaid($payment->order);

        return $payment;
    }

    /**
     * Check payment status from gateway.
     * Stub — implement per gateway.
     */
    public function checkStatus(Payment $payment): string
    {
        // TODO: Query actual gateway for real-time status
        // For now, return current stored status
        return $payment->status;
    }

    /**
     * Map gateway-specific status to our internal status.
     */
    protected function mapGatewayStatus(string $gateway, array $payload): string
    {
        // Midtrans status mapping
        if ($gateway === 'midtrans') {
            return match ($payload['transaction_status'] ?? '') {
                'capture', 'settlement' => 'success',
                'pending' => 'pending',
                'deny', 'cancel' => 'failed',
                'expire' => 'expired',
                'refund', 'partial_refund' => 'refunded',
                default => 'pending',
            };
        }

        // Xendit status mapping
        if ($gateway === 'xendit') {
            return match ($payload['status'] ?? '') {
                'PAID', 'SETTLED' => 'success',
                'PENDING' => 'pending',
                'FAILED' => 'failed',
                'EXPIRED' => 'expired',
                default => 'pending',
            };
        }

        return 'pending';
    }
}
