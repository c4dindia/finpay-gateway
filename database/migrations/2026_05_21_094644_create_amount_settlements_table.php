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
        Schema::create('amount_settlements', function (Blueprint $table) {
            $table->id();
            $table->string('accountId');
            $table->string('currency');
            $table->string('amount');
            $table->string('commission')->nullable();
            $table->string('checkout_id');
            $table->string('description');
            $table->string('payment_service');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amount_settlements');
    }
};
