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
        Schema::create('p10_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->references('id')->on('companies')->onDelete('cascade')->nullable();
            $table->string('accountId')->nullable();
            $table->string('redirect_url')->nullable();

            $table->string('inabit_widget_type')->default('Customer Account')->nullable();
            $table->string('inabit_widget_id')->nullable();
            $table->string('inabit_widget_balance')->nullable();
            $table->string('inabit_widget_api_key')->nullable();
            $table->string('inabit_merchant_name')->nullable();

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
        Schema::dropIfExists('p10_payment_methods');
    }
};
