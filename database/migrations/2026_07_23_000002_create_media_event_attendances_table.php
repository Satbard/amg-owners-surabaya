<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_event_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_event_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('media_registration_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->enum('status', ['hadir', 'tidak_hadir'])->default('tidak_hadir');
            $table->dateTime('scanned_at')->nullable();
            $table->timestamps();
            $table->unique(['media_event_id', 'media_registration_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_event_attendances');
    }
};
