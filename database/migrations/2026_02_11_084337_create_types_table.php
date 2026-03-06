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
    Schema::create('types', function (Blueprint $table) {
        $table->id('type_id'); // Custom Primary Key
        $table->string('type_name');
        $table->foreignId('type_user_id')->nullable()->constrained('users');
        $table->timestamp('type_log_datetime')->nullable();
        $table->boolean('type_inactive')->default(0);
    });
}
};
