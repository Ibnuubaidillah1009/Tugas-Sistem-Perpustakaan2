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
        DB::statement("ALTER TABLE borrowings MODIFY COLUMN status ENUM('menunggu_perizinan', 'dipinjam', 'dikembalikan', 'terlambat') DEFAULT 'menunggu_perizinan'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE borrowings MODIFY COLUMN status ENUM('dipinjam', 'dikembalikan', 'terlambat') DEFAULT 'dipinjam'");
    }
};
