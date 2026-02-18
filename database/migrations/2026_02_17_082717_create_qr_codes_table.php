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
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->string('name')->nullable();
            $table->enum('type', ['vcard', 'email', 'sms', 'url', 'social', 'text', 'wifi', 'event', 'facebook', 'twitter', 'linkedin', 'instagram', 'youtube'])->default('url');
            $table->json('data');  // Store all form data as JSON
            $table->json('settings')->nullable();  // Colors, logo, frame settings
            $table->string('file_path')->nullable();
            $table->boolean('is_dynamic')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_codes');
    }
};
