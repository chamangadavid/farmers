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
        Schema::table('monthly_reports', function (Blueprint $table) {
            
         $table->decimal('feed_cost',15,2)->default(0)->after('expenses');

            $table->decimal('medicine_cost',15,2)->default(0);

            $table->decimal('transport_cost',15,2)->default(0);

            $table->decimal('labour_cost',15,2)->default(0);

            $table->decimal('other_cost',15,2)->default(0);

            $table->integer('active_batches')->default(0);

            $table->integer('completed_batches')->default(0);

            $table->integer('birds_remaining')->default(0);

            $table->decimal('mortality_percentage',8,2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monthly_reports', function (Blueprint $table) {
            
            $table->dropColumn([
                'feed_cost',
                'medicine_cost',
                'transport_cost',
                'labour_cost',
                'other_cost',
                'active_batches',
                'completed_batches',
                'birds_remaining',
                'mortality_percentage'
            ]);
        });
    }
};
