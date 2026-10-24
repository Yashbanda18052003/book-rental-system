<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("
                ALTER TABLE reservations
                MODIFY COLUMN status ENUM('waiting', 'assigned', 'completed', 'available')
                NOT NULL DEFAULT 'waiting'
            ");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("UPDATE reservations SET status = 'waiting' WHERE status = 'available'");

            DB::statement("
                ALTER TABLE reservations
                MODIFY COLUMN status ENUM('waiting', 'assigned', 'completed')
                NOT NULL DEFAULT 'waiting'
            ");
        }
    }
};