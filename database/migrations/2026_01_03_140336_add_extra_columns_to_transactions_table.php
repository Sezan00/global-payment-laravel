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
            $table->string('wise_quote_id')->nullable()->after('status');
            $table->string('wise_transfer_id')->nullable()->after('status');
            $table->string('wise_recipient_id')->nullable()->after('status'); 
            $table->string('wise_status')->nullable()->after('status');
            $table->text('wise_error')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
           $table->dropColumn([
            'wise_quote_id',
            'wise_transfer_id',
            'wise_recipient_id',
            'wise_status',
            'wise_error'
           ]);
        });
    }
};
