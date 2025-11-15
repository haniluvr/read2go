<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the status enum to include 'pending_payment'
        DB::statement("ALTER TABLE book_loans MODIFY COLUMN status ENUM('pending_payment', 'active', 'returned', 'overdue', 'lost') DEFAULT 'pending_payment'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE book_loans MODIFY COLUMN status ENUM('active', 'returned', 'overdue', 'lost') DEFAULT 'active'");
    }
};
