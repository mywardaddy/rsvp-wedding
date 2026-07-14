<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PricingPackage;
use App\Mail\OrderCreatedMail;
use App\Mail\NewOrderNotificationMail;
use App\Mail\PaymentSuccessMail;
use App\Mail\PaymentReceivedNotificationMail;
use App\Services\AccountProvisioningService;
use Illuminate\Support\Facades\Mail;

class OrderService
{
    /**
     * Create a new order from validated data.
     */
    public function createOrder(array $data): Order
    {
        $package = PricingPackage::findOrFail($data['pricing_package_id']);

        $order = Order::create([
            'pricing_package_id' => $package->id,
            'user_id' => null, // Will be set after payment by AccountProvisioningService
            'customer_name' => $data['customer_name'],
            'customer_email' => $data['customer_email'],
            'customer_whatsapp' => $data['customer_whatsapp'],
            'groom_name' => $data['groom_name'],
            'bride_name' => $data['bride_name'],
            'wedding_date' => $data['wedding_date'],
            'package_name' => $package->name,
            'original_price' => $package->price,
            'discount_type' => $package->discount_type,
            'discount_value' => $package->discount_value,
            'discount_amount' => $package->discount_amount,
            'total_amount' => $package->discounted_price,
            'status' => 'pending_payment',
            'expired_at' => now()->addHours(24),
        ]);

        // Send notification emails
        $this->sendOrderCreatedNotifications($order);

        return $order;
    }

    /**
     * Mark order as paid and provision pengantin account.
     */
    public function markAsPaid(Order $order): Order
    {
        $order->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Send payment success notifications
        $this->sendPaymentSuccessNotifications($order);

        // Auto-provision pengantin account
        $provisioningService = new AccountProvisioningService();
        $provisioningService->provision($order);

        return $order;
    }

    /**
     * Cancel an order.
     */
    public function cancelOrder(Order $order): Order
    {
        $order->update([
            'status' => 'cancelled',
        ]);

        return $order;
    }

    /**
     * Send order created notifications.
     */
    protected function sendOrderCreatedNotifications(Order $order): void
    {
        // Email to customer
        Mail::to($order->customer_email)->queue(new OrderCreatedMail($order));

        // Email to all superadmins
        $superadmins = \App\Models\User::whereHas('role', fn($q) => $q->where('name', 'superadmin'))->get();
        foreach ($superadmins as $admin) {
            Mail::to($admin->email)->queue(new NewOrderNotificationMail($order));
        }
    }

    /**
     * Send payment success notifications.
     */
    protected function sendPaymentSuccessNotifications(Order $order): void
    {
        // Email to customer
        Mail::to($order->customer_email)->queue(new PaymentSuccessMail($order));

        // Email to all superadmins
        $superadmins = \App\Models\User::whereHas('role', fn($q) => $q->where('name', 'superadmin'))->get();
        foreach ($superadmins as $admin) {
            Mail::to($admin->email)->queue(new PaymentReceivedNotificationMail($order));
        }
    }
}
