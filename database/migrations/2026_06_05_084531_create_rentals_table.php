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
        Schema::create('rentals', function (Blueprint $table) {
    
            $table->id();
    
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();
    
            $table->foreignId('book_id')
                  ->constrained()
                  ->cascadeOnDelete();
    
            $table->date('start_date');
    
            $table->date('end_date');
    
            $table->date('returned_at')
                  ->nullable();
    
            $table->decimal('amount', 10, 2);
    
            $table->decimal('fine', 10, 2)
                  ->default(0);
    
            $table->enum('status', [
                'pending',
                'active',
                'returned',
                'overdue'
            ])->default('pending');
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
