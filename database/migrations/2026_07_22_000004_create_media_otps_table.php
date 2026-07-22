<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_otps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_registration_id')->constrained()->cascadeOnDelete();
            $table->string('otp', 6);
            $table->dateTime('expires_at');
            $table->dateTime('used_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_otps');
    }
};
