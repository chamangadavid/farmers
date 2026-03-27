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
        Schema::create('accident_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_type');
            $table->string('reporter_name');
            $table->string('reporter_email');
            $table->string('reporter_phone')->nullable();

            $table->date('accident_date');
            $table->time('accident_time');
            $table->string('location');

            $table->string('aircraft_registration')->nullable();
            $table->string('aircraft_type')->nullable();

            $table->text('description');
            $table->text('injuries')->nullable();
            $table->text('damage')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accident_reports');
    }
};
