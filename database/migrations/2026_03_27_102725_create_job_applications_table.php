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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            // Relationship
            // $table->foreignId('job_id')->constrained()->cascadeOnDelete();
             $table->foreignId('job_id')
                ->constrained('jobs')
                ->onDelete('cascade');

            // Applicant info
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('address')->nullable();
            $table->string('current_position')->nullable();
            $table->string('current_employer')->nullable();

            // Files
            $table->string('cv')->nullable();
            $table->string('nrc')->nullable();
            $table->string('grade12')->nullable();
            $table->string('degree')->nullable();
            $table->string('masters')->nullable();
            $table->string('other_documents')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
