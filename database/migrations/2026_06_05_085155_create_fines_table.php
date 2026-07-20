<?php

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
    Schema::create('fines', function (Blueprint $table) {

        $table->id();

        $table->foreignId('rental_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->foreignId('user_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->integer('late_days')
              ->default(0);

        $table->decimal('fine_amount', 10, 2);

        $table->enum('status', [
            'pending',
            'paid'
        ])->default('pending');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fines');
    }
};
