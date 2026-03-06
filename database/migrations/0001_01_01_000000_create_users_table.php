<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // 1. Roles Table (Mapped from Image)
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('role_name');
            $table->boolean('role_inactive')->default(0);
            $table->timestamps();
        });

        // 2. Users Table (Integrated with Platform Database fields)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->foreignId('role_id')->nullable()->constrained('roles');
            $table->timestamp('user_log_datetime')->nullable();
            $table->boolean('user_inactive')->default(0);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes(); // Enabled for your archive requirement
        });

        // 3. Sessions Table (Fixes the previous login error)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }
};