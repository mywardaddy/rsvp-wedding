<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->enum('status', ['draft', 'active', 'done'])->default('active')->after('is_active');
        });

        // Sync existing data: set status based on current is_active value
        DB::table('events')->where('is_active', true)->update(['status' => 'active']);
        DB::table('events')->where('is_active', false)->update(['status' => 'done']);
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
