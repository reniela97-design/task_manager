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
    Schema::create('systems', function (Blueprint $table) {
        // Custom Primary Key
        $table->id('system_id'); 
        
        $table->string('system_name');
        
        // Relationship to Users (nullable in case user is deleted)
        $table->foreignId('system_user_id')->nullable()->constrained('users');
        
        // Logs and Status
        $table->timestamp('system_log_datetime')->nullable();
        $table->boolean('system_inactive')->default(0);
    });
}
};
