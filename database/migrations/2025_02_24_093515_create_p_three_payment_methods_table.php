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
        Schema::create('xOne', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->references('id')->on('companies')->onDelete('cascade')->nullable();
            $table->string('accountId')->nullable();
            $table->string('redirect_url')->nullable();
            $table->string('api_key')->nullable();
            $table->string('b_token')->nullable();
            $table->string('status')->default('1');
            $table->string('widget_id')->default('W1KGL0QQ')->nullable();    //added
            $table->string('script_url')->default('topexch.net')->nullable();   //added
            $table->string('widget_secret_key')->default('RSWjSEwzTc2XFy6t')->nullable();    //added
            $table->string('success_url')->nullable(); //added
            $table->string('error_url')->nullable(); //added
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xOne');
    }
};
