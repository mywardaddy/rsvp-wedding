<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checkins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_id')->constrained('guests')->cascadeOnDelete();
            $table->foreignId('scanner_user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('checked_in_at');
            $table->enum('method', ['qr_scan', 'manual', 'override'])->default('qr_scan');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['guest_id', 'checked_in_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checkins');
    }
};
