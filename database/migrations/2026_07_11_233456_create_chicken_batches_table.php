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
        Schema::create('chicken_batches', function (Blueprint $table) {
            $table->id();
            $table->date('arrival_date');
            $table->string('batch_number')->unique();
            $table->string('batch_name')->nullable();
            $table->integer('batch_size');
            $table->integer('mortality')->default(0);
            $table->integer('birds_sold')->default(0);
            $table->integer('birds_remaining')->default(0);
            $table->date('estimated_sale_date');
            $table->string('breed')->nullable();
            $table->string('supplier')->nullable();
            $table->decimal('purchase_price',10,2)->default(0);
            $table->enum('status',['Growing', 'Ready for Sale', 'Selling In Progress', 'Selling', 'Sold Out','Completed', 'Closed'])->default('Growing');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chicken_batches');
    }
};
