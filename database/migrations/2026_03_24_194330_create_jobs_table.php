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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['Full-time', 'Part-time', 'Contract', 'Temporary', 'Internship','Other'])->default('Full-time');
            $table->string('location');
            $table->dateTime('deadline');
            $table->text('description');
            $table->text('requirements')->nullable(); // Store JSON array as text
            $table->string('pdf_file')->nullable(); // Optional PDF
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
