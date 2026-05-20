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
        Schema::create('upi_merchants', function (Blueprint $table) {
            $table->id();
            $table->string('mid');
            $table->string('vpa');
            $table->string('limitPerDay');
            $table->string('limitPerMonth');
            $table->string('limitPerYear');
            $table->string('status')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upi_merchants');
    }
};
