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
        Schema::table('accident_reports', function (Blueprint $table) {
             $table->string('status')->default('Reported');
             $table->string('follow_up_file')->nullable();
             $table->string('summary')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accident_reports', function (Blueprint $table) {
            //
        });
    }
};
