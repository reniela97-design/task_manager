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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_title'); // Pananglitan: Monthly Task Summary
            $table->string('report_type');  // Pananglitan: Tasks, Projects, or Users
            $table->foreignId('generated_by')->constrained('users')->onDelete('cascade'); // Link sa User
            $table->json('report_data')->nullable(); // I-save ang snapshot sa data (para dili mausab bisan ma-update ang tasks)
            $table->text('remarks')->nullable(); // Optional notes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};