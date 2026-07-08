<?php

use App\Models\Registration;
use App\Services\BarcodeService;
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
        Schema::table('registrations', function (Blueprint $table) {
            $table->string('barcode_token', 32)
                ->unique()
                ->nullable()
                ->after('member_number')
                ->index();
        });

        // Backfill: Generate barcode_token for all existing members that have member_number
        $members = Registration::whereNotNull('member_number')->get();

        foreach ($members as $member) {
            $token = BarcodeService::generateToken();

            // Ensure uniqueness
            while (Registration::where('barcode_token', $token)->exists()) {
                $token = BarcodeService::generateToken();
            }

            $member->barcode_token = $token;
            $member->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn('barcode_token');
        });
    }
};
