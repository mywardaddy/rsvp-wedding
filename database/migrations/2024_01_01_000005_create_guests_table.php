<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignId('guest_group_id')->nullable()->constrained('guest_groups')->nullOnDelete();
            $table->string('name');
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->enum('category', ['vip', 'reguler', 'keluarga', 'sahabat'])->default('reguler');
            $table->integer('max_companions')->default(1);
            $table->text('notes')->nullable();
            $table->string('qr_code')->unique()->nullable();
            $table->string('slug')->unique();
            $table->timestamps();

            $table->index(['event_id', 'category']);
            $table->index(['event_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
