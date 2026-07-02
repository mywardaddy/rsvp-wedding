<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_id')->constrained('guests')->cascadeOnDelete();
            $table->enum('sent_via', ['whatsapp', 'email', 'manual'])->default('manual');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->string('link');
            $table->timestamps();

            $table->index('guest_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
