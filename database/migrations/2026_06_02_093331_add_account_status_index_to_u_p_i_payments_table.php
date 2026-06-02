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
        Schema::table('u_p_i_payments', function (Blueprint $table) {
            $table->index(['accountId', 'status'], 'idx_account_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('u_p_i_payments', function (Blueprint $table) {
            $table->dropIndex('idx_account_status');
        });
    }
};
