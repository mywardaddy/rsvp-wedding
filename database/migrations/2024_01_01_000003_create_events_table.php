<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('groom_name');
            $table->string('bride_name');
            $table->date('date');
            $table->time('time_start');
            $table->time('time_end')->nullable();
            $table->string('venue_name');
            $table->text('venue_address');
            $table->decimal('venue_lat', 10, 7)->nullable();
            $table->decimal('venue_lng', 10, 7)->nullable();
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->json('love_story')->nullable();
            $table->json('gallery')->nullable();
            $table->string('music_url')->nullable();
            $table->string('theme_color')->default('#C9B037');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
