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
        Schema::create('alikassa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('accountId')->nullable();
            $table->string('redirect_url')->nullable();
            $table->string('alikassa_uuid')->nullable();
            $table->string('alikassa_id')->nullable();
            $table->string('alikassa_service')->nullable(); //like 'payment_card_kzt' , 'payment_card_rub'
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
        Schema::dropIfExists('alikassa');
    }
};
