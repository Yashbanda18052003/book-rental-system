<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {

            $table->unsignedBigInteger('subscription_id')
                  ->nullable()
                  ->after('rental_id');

            $table->unsignedBigInteger('fine_id')
                  ->nullable()
                  ->after('subscription_id');

            $table->enum('payment_type', [
                'rental',
                'subscription',
                'fine'
            ])->default('rental');

        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {

            $table->dropColumn([
                'subscription_id',
                'fine_id',
                'payment_type'
            ]);

        });
    }
};