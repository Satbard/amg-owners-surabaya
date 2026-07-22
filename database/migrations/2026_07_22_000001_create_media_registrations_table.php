<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_registrations', function (Blueprint $table) {

            $table->id();

            // Personal Information
            $table->string('full_name');
            $table->string('media_name');
            $table->json('position'); // multi-select, stored as JSON array

            // Contact
            $table->string('phone');
            $table->string('email');

            // Media Information
            $table->string('social_media');
            $table->string('followers')->nullable(); // string to allow "100k", "1M", etc.

            $table->enum('media_type', [
                'Print',
                'Online',
                'TV',
                'Radio',
                'Digital Creator',
                'Community Media',
                'Others',
            ]);

            // Competition Registration
            $table->enum('competition_category', [
                'Photography',
                'Videography / Reels',
            ]);

            $table->enum('equipment_used', [
                'Camera',
                'Smartphone',
                'Drone',
            ]);

            // Terms & Agreement
            $table->boolean('terms_agreed')->default(false);

            // Status
            $table->enum('status', [
                'Pending',
                'Approved',
                'Rejected',
            ])->default('Pending');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_registrations');
    }
};
