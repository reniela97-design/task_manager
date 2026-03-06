<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('categories', function (Blueprint $table) {
        $table->id('category_id'); // Custom Primary Key
        $table->string('category_name');
        $table->foreignId('category_user_id')->nullable()->constrained('users');
        $table->timestamp('category_log_datetime')->nullable();
        $table->boolean('category_inactive')->default(0);
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
