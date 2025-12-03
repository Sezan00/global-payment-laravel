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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');

            $table->foreignId('source_country_currency_id')
                   ->constrained('country_currencies')
                   ->onDelete('cascade');

            $table->foreignId('target_country_currency_id')
                   ->constrained('country_currencies')
                   ->onDelete('cascade');
           

            $table->decimal('amount', 15, 2);

            $table->foreignId('exhange_rate_id')
                  ->constrained('exhange_rates')
                  ->onDelete('cascade');
        

            $table->enum('status', ['pending', 'used', 'expired'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
