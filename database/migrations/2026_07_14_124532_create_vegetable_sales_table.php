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
        Schema::create('vegetable_sales', function (Blueprint $table) {
            $table->id();

            $table->foreignId('vegetable_harvest_id')->constrained('vegetable_harvests')->cascadeOnDelete();
            $table->date('sale_date');
            $table->string('invoice_number')->unique();
            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            $table->decimal('quantity',10,2);
            $table->decimal('unit_price',10,2);
            $table->decimal('total_amount',12,2);
            $table->enum('payment_method',[
                'Cash',
                'Mobile Money',
                'Bank Transfer',
                'Cheque'
            ]);

            $table->string('status')->default('Completed');

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vegetable_sales');
    }
};
