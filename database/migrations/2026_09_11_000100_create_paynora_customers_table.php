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
        Schema::create('paynora_customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('accountId')->nullable();
            // Customer id returned by Paynora, sent back on every payment.
            $table->string('provider_customer_id')->nullable();
            // Mandatory fields of POST /customer/create
            $table->string('merchant_customer_id')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('country_code')->nullable();
            $table->string('birth_date')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('address1')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('status')->default('1');
            $table->timestamps();

            $table->index('provider_customer_id');
            $table->unique(['accountId', 'merchant_customer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paynora_customers');
    }
};
