<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {

            $table->id();

            $table->string('full_name');

            $table->string('nickname');

            $table->string('birth_place');

            $table->date('birth_date');

            $table->text('address');

            $table->string('phone');

            $table->string('email')->nullable();

            $table->string('instagram')->nullable();

            $table->string('occupation');

            $table->enum('shirt_size', [
                'XXS',
                'XS',
                'S',
                'M',
                'L',
                'XL',
                'XXL',
                'XXXL'
            ]);

            $table->string('vehicle_model');

            $table->year('vehicle_year');

            $table->string('vehicle_color');

            $table->string('license_plate');

            $table->enum('membership_status', [
                'Pending',
                'Approved',
                'Rejected'
            ])->default('Pending');

            $table->timestamps();

            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};