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
        Schema::create('book_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->foreignId('library_id')->constrained()->onDelete('cascade');
            $table->enum('delivery_type', ['home', 'pickup'])->default('pickup');
            $table->text('delivery_address')->nullable();
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->dateTime('borrowed_at');
            $table->date('due_date'); // borrowed_at + 7 days
            $table->dateTime('returned_at')->nullable();
            $table->enum('return_method', ['pickup', 'dropoff'])->nullable();
            $table->enum('status', ['active', 'returned', 'overdue', 'lost'])->default('active');
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index(['book_id', 'status']);
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_loans');
    }
};
