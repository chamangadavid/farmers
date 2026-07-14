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
        Schema::create('vegetable_productions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('vegetable_type_id')->constrained()->cascadeOnDelete();
            $table->string('batch_number')->unique();
            $table->string('season');
            $table->enum('quarter',[
                'Q1',
                'Q2',
                'Q3',
                'Q4',
                'Q5',
            ]);

            $table->date('planting_date');
            $table->date('expected_harvest_date');
            $table->date('harvest_end_date')->nullable();
            $table->decimal('land_size',10,2);
            $table->enum('land_unit',[
                'Acres',
                'Hectares'
            ])->default('Acres');

            $table->decimal('expected_yield',12,2)
                ->default(0);

            $table->decimal('actual_yield',12,2)
                ->default(0);

            $table->enum('status',[

                'Planning',

                'Growing',

                'Harvesting',

                'Completed',
                'Selling',
                'Closed',

            ])->default('Planning');

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vegetable_productions');
    }
};
