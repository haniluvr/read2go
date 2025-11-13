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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('loan_id')->nullable()->constrained('book_loans')->onDelete('set null');
            $table->foreignId('penalty_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('PHP');
            $table->string('xendit_payment_id')->nullable()->unique();
            $table->enum('status', ['pending', 'paid', 'failed'])->default('pending');
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index('xendit_payment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
