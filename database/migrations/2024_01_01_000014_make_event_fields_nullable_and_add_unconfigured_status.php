<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Make event fields nullable so unconfigured events can exist
        Schema::table('events', function (Blueprint $table) {
            $table->string('title')->nullable()->change();
            $table->string('slug')->nullable()->change();
            $table->string('groom_name')->nullable()->change();
            $table->string('bride_name')->nullable()->change();
            $table->date('date')->nullable()->change();
            $table->time('time_start')->nullable()->change();
            $table->string('venue_name')->nullable()->change();
            $table->text('venue_address')->nullable()->change();
        });

        // Update status enum to include 'unconfigured'
        // Drop old enum column and recreate with new values
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->enum('status', ['unconfigured', 'draft', 'active', 'done'])
                  ->default('unconfigured')
                  ->after('is_active');
        });

        // Re-sync existing data: set status based on is_active
        DB::table('events')
            ->whereNotNull('title')
            ->whereNotNull('groom_name')
            ->where('is_active', true)
            ->update(['status' => 'active']);

        DB::table('events')
            ->whereNotNull('title')
            ->whereNotNull('groom_name')
            ->where('is_active', false)
            ->update(['status' => 'done']);
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('title')->nullable(false)->change();
            $table->string('slug')->nullable(false)->change();
            $table->string('groom_name')->nullable(false)->change();
            $table->string('bride_name')->nullable(false)->change();
            $table->date('date')->nullable(false)->change();
            $table->time('time_start')->nullable(false)->change();
            $table->string('venue_name')->nullable(false)->change();
            $table->text('venue_address')->nullable(false)->change();
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->enum('status', ['draft', 'active', 'done'])
                  ->default('active')
                  ->after('is_active');
        });
    }
};
