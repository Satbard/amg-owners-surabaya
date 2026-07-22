<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('media_registrations', function (Blueprint $table) {
            $table->string('barcode_token', 8)->nullable()->unique()->after('equipment_used');
        });
    }

    public function down(): void
    {
        Schema::table('media_registrations', function (Blueprint $table) {
            $table->dropColumn('barcode_token');
        });
    }
};
