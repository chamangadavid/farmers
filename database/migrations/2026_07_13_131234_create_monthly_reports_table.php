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
        Schema::create('monthly_reports', function (Blueprint $table) {

            $table->id();
            $table->integer('month');
            $table->integer('year');

            $table->decimal('revenue', 15, 2)->default(0);
            $table->decimal('expenses', 15, 2)->default(0);
            $table->decimal('profit', 15, 2)->default(0);

            $table->integer('birds_sold')->default(0);
            $table->integer('total_birds')->default(0);

            $table->decimal('mortality', 8, 2)->default(0);

            $table->timestamps();

            $table->unique(['month','year']);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_reports');
    }
};
