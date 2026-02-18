<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('qr_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Insert default QR types
        $types = [
            ['name' => 'vCard Contact', 'slug' => 'vcard', 'icon' => 'fas fa-address-card', 'description' => 'Share contact information', 'sort_order' => 1],
            ['name' => 'Email', 'slug' => 'email', 'icon' => 'fas fa-envelope', 'description' => 'Send an email', 'sort_order' => 2],
            ['name' => 'SMS', 'slug' => 'sms', 'icon' => 'fas fa-sms', 'description' => 'Send a text message', 'sort_order' => 3],
            ['name' => 'URL', 'slug' => 'url', 'icon' => 'fas fa-link', 'description' => 'Open a website', 'sort_order' => 4],
            ['name' => 'Text', 'slug' => 'text', 'icon' => 'fas fa-font', 'description' => 'Display plain text', 'sort_order' => 5],
            ['name' => 'WiFi', 'slug' => 'wifi', 'icon' => 'fas fa-wifi', 'description' => 'Connect to WiFi', 'sort_order' => 6],
            ['name' => 'Event', 'slug' => 'event', 'icon' => 'fas fa-calendar', 'description' => 'Add to calendar', 'sort_order' => 7],
            ['name' => 'Facebook', 'slug' => 'facebook', 'icon' => 'fab fa-facebook', 'description' => 'Facebook profile/page', 'sort_order' => 8],
            ['name' => 'Twitter', 'slug' => 'twitter', 'icon' => 'fab fa-twitter', 'description' => 'Twitter profile', 'sort_order' => 9],
            ['name' => 'LinkedIn', 'slug' => 'linkedin', 'icon' => 'fab fa-linkedin', 'description' => 'LinkedIn profile', 'sort_order' => 10],
            ['name' => 'Instagram', 'slug' => 'instagram', 'icon' => 'fab fa-instagram', 'description' => 'Instagram profile', 'sort_order' => 11],
            ['name' => 'YouTube', 'slug' => 'youtube', 'icon' => 'fab fa-youtube', 'description' => 'YouTube channel/video', 'sort_order' => 12],
        ];

        DB::table('qr_types')->insert($types);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_types');
    }
};
