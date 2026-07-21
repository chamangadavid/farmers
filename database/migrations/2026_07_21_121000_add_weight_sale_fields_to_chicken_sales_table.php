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
        Schema::table('chicken_sales', function (Blueprint $table) {
            
        $table->enum('sale_type', ['Per Bird', 'Per Kg'])->default('Per Bird')->after('chicken_batch_id');
        $table->decimal('total_weight', 10, 2)->nullable()->after('quantity');
        $table->decimal('price_per_kg', 10, 2)->nullable()->after('unit_price');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chicken_sales', function (Blueprint $table) {
            //
        });
    }
};
