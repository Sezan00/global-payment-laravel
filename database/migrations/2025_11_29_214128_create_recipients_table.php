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
        Schema::create('recipients', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->foreignId('target_country_currency_id')
                   ->constrained('country_currencies')
                   ->onDelete('cascade');
                   
            $table->string('receive_type');
                        
            $table->string('full_name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            $table->string('city')->nullable();
            $table->string('address')->nullable();

        
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();


            $table->string('wallet_type')->nullable();
            $table->string('wallet_number')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipients');
    }
};
