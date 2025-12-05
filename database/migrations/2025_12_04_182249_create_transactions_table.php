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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreignId('quotation_id')->constrained()->onDelete('cascade');
            $table->foreignId('recipient_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('source_country_currency_id');
            $table->unsignedBigInteger('target_country_currency_id');
            $table->unsignedBigInteger('source_of_fund_id')->nullable();
            $table->decimal('amount', 15, 2);
            $table->decimal('rate', 15, 6);
            $table->decimal('converted_amount', 15, 2);
            $table->enum('status', ['pending', 'process', 'complete', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
            Schema::dropIfExists('transactions');
                Schema::dropIfExists('quotations');


    }
};
