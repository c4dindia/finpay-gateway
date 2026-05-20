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
        Schema::create('direpay', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('accountId')->nullable();
            $table->string('redirect_url')->nullable();
            $table->string('direpay_api_email')->nullable();
            $table->string('direpay_api_password')->nullable();
            $table->string('direpay_api_secret')->nullable();
            $table->string('api_key')->nullable();
            $table->string('b_token')->nullable();
            $table->string('status')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('direpay');
    }
};
