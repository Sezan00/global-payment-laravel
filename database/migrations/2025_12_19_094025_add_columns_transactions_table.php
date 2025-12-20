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
          Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('purpose_of_transfer_id')->after('target_country_currency_id')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'relation_id',
                'purpose_of_transfer_id'
            ]);
        });
    }
};
