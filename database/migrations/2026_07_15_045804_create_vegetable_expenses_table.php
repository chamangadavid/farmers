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
        Schema::create('vegetable_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vegetable_production_id')->constrained()->cascadeOnDelete();
            $table->date('expense_date');
            $table->string('expense_category');
            $table->string('description')->nullable();
            $table->string('supplier')->nullable();
            $table->string('receipt_number')->nullable();
            $table->decimal('quantity',12,2)->default(1);
            $table->string('unit')->nullable();
            $table->decimal('unit_cost',12,2);
            $table->decimal('amount',12,2);
            $table->string('payment_method');
            $table->string('reference_number')->nullable();
            $table->string('status')->default('Paid');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vegetable_expenses');
    }
};
