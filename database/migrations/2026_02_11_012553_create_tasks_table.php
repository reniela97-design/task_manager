<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            // KINI ANG MGA MISSING COLUMNS NGA GIPANGITA SA IMONG ERROR LOG:
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('task_date');
            $table->string('status')->default('Open');
            $table->string('priority')->default('Medium');
            $table->string('category')->nullable();
            $table->string('type')->default('Standard');
            
            // Note: Kung naa kay separate tables para sa projects ug clients, 
            // gamita ang foreignId. Kung string lang, gamita ang string().
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            
            // I-add pud ni kung gusto nimo itago ang karaan nimo nga fields:
            $table->string('project_name')->nullable();
            $table->string('client_name')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};