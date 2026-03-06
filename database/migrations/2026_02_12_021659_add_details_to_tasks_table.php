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
    Schema::table('tasks', function (Blueprint $table) {
        // Mag check ta kung naa na ba ang column aron dili mo-error og "Duplicate"
        if (!Schema::hasColumn('tasks', 'project_id')) {
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
        }
        if (!Schema::hasColumn('tasks', 'client_id')) {
            $table->foreignId('client_id')->nullable()->constrained()->onDelete('cascade');
        }
        if (!Schema::hasColumn('tasks', 'priority')) {
            $table->string('priority')->nullable();
        }
        if (!Schema::hasColumn('tasks', 'category')) {
            $table->string('category')->nullable();
        }
        if (!Schema::hasColumn('tasks', 'type')) {
            $table->string('type')->nullable(); // Kani ang importante
        }
    });

}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            //
        });
    }
};
