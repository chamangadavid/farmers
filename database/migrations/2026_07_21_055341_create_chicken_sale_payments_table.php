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
        Schema::create('chicken_sale_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('chicken_sale_id')
                ->constrained('chicken_sales')
                ->onDelete('cascade');

            $table->date('payment_date');

            $table->decimal('amount', 15, 2);

            $table->enum('payment_method', [
                'Cash',
                'Credit',
                'Card',
                'Cheque',
                'Mobile Money',
                'Bank Transfer'
            ]);

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chicken_sale_payments');
    }
};
