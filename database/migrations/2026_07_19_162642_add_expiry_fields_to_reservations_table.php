<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->timestamp('available_at')->nullable()->after('status');
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("
                ALTER TABLE reservations
                MODIFY COLUMN status ENUM('waiting', 'assigned', 'completed', 'available', 'expired')
                NOT NULL DEFAULT 'waiting'
            ");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("UPDATE reservations SET status = 'waiting' WHERE status = 'expired'");
        }

        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('available_at');
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("
                ALTER TABLE reservations
                MODIFY COLUMN status ENUM('waiting', 'assigned', 'completed', 'available')
                NOT NULL DEFAULT 'waiting'
            ");
        }
    }
};