<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Identify WHO
            $table->string('action');       // WHAT they did (e.g., "User logged in")
            $table->string('module')->nullable(); // WHICH part (e.g., "Tasks", "Auth")
            $table->string('ip_address')->nullable(); // WHERE (IP Address)
            $table->timestamp('created_at'); // WHEN (Time)
            // Note: We don't strictly need updated_at for logs
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};