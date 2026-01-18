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
        Schema::table('recipients', function (Blueprint $table) {

          $table->foreignId('source_country_currency_id')
              ->after('target_country_currency_id')
              ->nullable()
              ->constrained('country_currencies')
              ->cascadeOnDelete();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipients', function (Blueprint $table) {
              $table->dropForeign(['source_country_currency_id']);
              $table->dropColumn('source_country_currency_id');
        });
    }
};
