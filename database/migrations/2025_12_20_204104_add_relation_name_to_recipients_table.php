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
            $table->unsignedBigInteger('relation_id')->after('full_name')->nullable();

                $table->foreign('relation_id')
          ->references('id')
          ->on('relations')
          ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipients', function (Blueprint $table) {
            $table->dropColumn('relation_id');
            $table->dropForeign(['relation_id']);
        });
    }
};
