<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add subscription tracking fields to orders
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('expired_at')->nullable()->after('paid_at');
            $table->timestamp('account_created_at')->nullable()->after('expired_at');
        });

        // Add subscription fields to users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('order_id')->nullable()->after('is_active')
                  ->constrained('orders')->nullOnDelete();
            $table->enum('subscription_status', ['active', 'expired', 'suspended'])
                  ->default('active')->after('order_id');
            $table->timestamp('activated_at')->nullable()->after('subscription_status');
        });

        // Add order reference to events
        Schema::table('events', function (Blueprint $table) {
            $table->foreignId('order_id')->nullable()->after('user_id')
                  ->constrained('orders')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn('order_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn(['order_id', 'subscription_status', 'activated_at']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['expired_at', 'account_created_at']);
        });
    }
};
