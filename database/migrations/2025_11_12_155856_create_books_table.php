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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('library_id')->constrained()->onDelete('cascade');
            $table->string('isbn')->nullable();
            $table->string('title');
            $table->string('author')->nullable();
            $table->text('description')->nullable();
            $table->string('cover_url')->nullable();
            $table->enum('status', ['available', 'loaned', 'unavailable'])->default('available');
            $table->json('metadata')->nullable(); // Store additional API data
            $table->timestamps();
            
            $table->index(['library_id', 'status']);
            $table->index('isbn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
