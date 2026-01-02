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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->after('country_id')->nullable();
            $table->date('dob')->after('country_id')->nullable();
            $table->string('address_line1')->after('country_id')->nullable();
            $table->string('city')->after('country_id')->nullable();
            $table->string('post_code')->after('country_id')->nullable();
            $table->string('wise_provider_id')->after('country_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'dob',
                'address_line1',
                'city',
                'post_code',
                'wise_provider_id'
            ]);
        });
    }
};
