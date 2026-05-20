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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('accountId');
            $table->string('user_id')->nullable();
            $table->string('email');
            $table->string('password');
            // $table->string('payment_partner')->nullable();
            // $table->string('redirect_url')->nullable();
            // $table->string('api_key')->nullable();
            // $table->string('b_token')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
