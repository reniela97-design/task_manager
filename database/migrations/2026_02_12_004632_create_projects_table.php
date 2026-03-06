<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Project Name
            // Kung naa kay Clients table, i-link nato. Kung wala, himua ranig string('client')
            $table->foreignId('client_id')->nullable()->constrained('clients')->onDelete('set null'); 
            $table->string('branch')->nullable();
            $table->text('address')->nullable();
            $table->string('status')->default('In Progress'); // Default status
            $table->integer('completion_percent')->default(0); // Progress bar
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};