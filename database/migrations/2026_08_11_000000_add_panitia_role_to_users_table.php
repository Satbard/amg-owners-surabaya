<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement(
                "ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin', 'panitia') NOT NULL DEFAULT 'admin'"
            );
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement(
                "ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin') NOT NULL DEFAULT 'admin'"
            );
        }
    }
};
