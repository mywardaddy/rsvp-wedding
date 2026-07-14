<?php

namespace App\Services;

use App\Mail\AccountCreatedMail;
use App\Models\Event;
use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AccountProvisioningService
{
    /**
     * Provision a pengantin account after successful payment.
     * Creates user, links order, sets up event, and sends credentials email.
     *
     * This method is idempotent — calling it twice for the same order will not
     * create duplicate accounts.
     */
    public function provision(Order $order): ?User
    {
        // Guard: don't provision if already done
        if ($order->isAccountCreated()) {
            Log::info("AccountProvisioning: Order {$order->order_number} already provisioned, skipping.");
            return $order->user;
        }

        // Guard: only provision paid orders
        if (!$order->isPaid()) {
            Log::warning("AccountProvisioning: Order {$order->order_number} is not paid, skipping.");
            return null;
        }

        $plainPassword = $this->generateSecurePassword();
        $username = $this->generateUniqueUsername($order->groom_name, $order->bride_name);

        $user = DB::transaction(function () use ($order, $username, $plainPassword) {
            // Get pengantin role
            $pengantinRole = Role::where('name', 'pengantin')->firstOrFail();

            // Create pengantin user
            $user = User::create([
                'name' => $order->groom_name . ' & ' . $order->bride_name,
                'email' => $order->customer_email,
                'password' => $plainPassword, // Will be auto-hashed by User model cast
                'role_id' => $pengantinRole->id,
                'phone' => $order->customer_whatsapp,
                'is_active' => true,
                'order_id' => $order->id,
                'subscription_status' => 'active',
                'activated_at' => now(),
            ]);

            // Link order to user
            $order->update([
                'user_id' => $user->id,
                'account_created_at' => now(),
            ]);

            // The UserObserver will automatically create an unconfigured Event
            // for the new pengantin user. We just need to link the order_id to it.
            $event = $user->clientEvent;
            if ($event) {
                $event->update([
                    'order_id' => $order->id,
                    'groom_name' => $order->groom_name,
                    'bride_name' => $order->bride_name,
                    'date' => $order->wedding_date,
                ]);
            }

            return $user;
        });

        // Send credentials email (outside transaction so it doesn't block)
        try {
            Mail::to($order->customer_email)->queue(
                new AccountCreatedMail($order, $user, $plainPassword)
            );
            Log::info("AccountProvisioning: Credentials email queued for {$order->customer_email}");
        } catch (\Throwable $e) {
            // Don't fail the whole process if email fails
            Log::error("AccountProvisioning: Failed to send email for order {$order->order_number}: {$e->getMessage()}");
        }

        Log::info("AccountProvisioning: Account created for order {$order->order_number}, user ID: {$user->id}");

        return $user;
    }

    /**
     * Generate a unique username from groom and bride names.
     * Format: firstname_groom.firstname_bride (lowercase, no spaces)
     * Appends number if already taken.
     */
    public function generateUniqueUsername(string $groomName, string $brideName): string
    {
        // Extract first names and clean them
        $groomFirst = $this->extractFirstName($groomName);
        $brideFirst = $this->extractFirstName($brideName);

        $baseUsername = strtolower($groomFirst . '.' . $brideFirst);
        // Remove any non-alphanumeric characters except dots
        $baseUsername = preg_replace('/[^a-z0-9.]/', '', $baseUsername);

        // Ensure minimum length
        if (strlen($baseUsername) < 3) {
            $baseUsername = 'user.' . $baseUsername;
        }

        $username = $baseUsername;
        $counter = 1;

        // Check uniqueness against existing user names (used as display/username)
        while (User::where('email', $username . '@generated.local')->exists()
            || User::where('name', 'like', '%' . $username . '%')->where('email', $username . '@%')->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }

    /**
     * Extract the first name from a full name string.
     */
    private function extractFirstName(string $fullName): string
    {
        $parts = preg_split('/\s+/', trim($fullName));
        return $parts[0] ?? 'user';
    }

    /**
     * Generate a secure password meeting all requirements:
     * - Min 12 characters
     * - Uppercase, lowercase, numbers, symbols
     */
    public function generateSecurePassword(int $length = 14): string
    {
        $uppercase = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        $lowercase = 'abcdefghjkmnpqrstuvwxyz';
        $numbers = '23456789';
        $symbols = '@#$!%&*?';

        // Guarantee at least one of each type
        $password = '';
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $symbols[random_int(0, strlen($symbols) - 1)];

        // Fill remaining length from all character pools
        $allChars = $uppercase . $lowercase . $numbers . $symbols;
        for ($i = 4; $i < $length; $i++) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }

        // Shuffle to avoid predictable pattern (first 4 chars always same type)
        return str_shuffle($password);
    }
}
