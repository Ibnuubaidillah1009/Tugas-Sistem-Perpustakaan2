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
        // First change the enum column to use Indonesian values
        DB::statement("ALTER TABLE borrowings MODIFY COLUMN status ENUM('dipinjam', 'dikembalikan', 'terlambat') DEFAULT 'dipinjam'");

        // Then update existing records to use Indonesian status values
        DB::statement("UPDATE borrowings SET status = CASE
            WHEN status = 'borrowed' THEN 'dipinjam'
            WHEN status = 'returned' THEN 'dikembalikan'
            WHEN status = 'overdue' THEN 'terlambat'
            ELSE status END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to English status values
        DB::statement("UPDATE borrowings SET status = CASE
            WHEN status = 'dipinjam' THEN 'borrowed'
            WHEN status = 'dikembalikan' THEN 'returned'
            WHEN status = 'terlambat' THEN 'overdue'
            ELSE status END");

        // Change the enum column back to English values
        DB::statement("ALTER TABLE borrowings MODIFY COLUMN status ENUM('borrowed', 'returned', 'overdue') DEFAULT 'borrowed'");
    }
};
