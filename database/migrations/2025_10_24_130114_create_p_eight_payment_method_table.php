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
        Schema::create('p_eight_payment_method', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->references('id')->on('companies')->onDelete('cascade')->nullable();
            $table->string('accountId')->nullable();
            $table->string('redirect_url')->nullable();
            $table->string('luqapay_apikey')->nullable();
            $table->string('luqapay_secretkey')->nullable();
            $table->string('luqapay_mid')->nullable();

            $table->string('luqapay_subscription_apikey')->nullable();
            $table->string('luqapay_subscription_secretkey')->nullable();
            $table->string('luqapay_subscription_mid')->nullable();

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
        Schema::dropIfExists('p_eight_payment_method');
    }
};
