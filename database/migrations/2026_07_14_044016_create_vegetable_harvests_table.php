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
        Schema::create('vegetable_harvests', function (Blueprint $table) {
            $table->id();

    $table->foreignId('vegetable_production_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->date('harvest_date');

    $table->string('grade')->nullable();

    $table->decimal('quantity',12,2);

    $table->decimal('unit_price',12,2)->default(0);

    $table->decimal('estimated_value',12,2)->default(0);

    $table->decimal('waste',12,2)->default(0);

    $table->string('harvested_by')->nullable();

    $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vegetable_harvests');
    }
};
