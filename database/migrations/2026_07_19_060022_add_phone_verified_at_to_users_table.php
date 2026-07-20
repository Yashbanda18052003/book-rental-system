<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('phone_verified_at')->nullable()->after('phone');
        });

        // Existing accounts predate this feature — treat them as already verified
        // so this migration doesn't lock out everyone who already has an account.
        // Only registrations from this point forward go through OTP verification.
        \Illuminate\Support\Facades\DB::table('users')->update([
            'phone_verified_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone_verified_at');
        });
    }
};
