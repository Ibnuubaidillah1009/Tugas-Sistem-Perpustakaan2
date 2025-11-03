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
        Schema::table('borrowings', function (Blueprint $table) {
            $table->enum('status_indonesia', ['dipinjam', 'dikembalikan', 'terlambat'])->default('dipinjam')->after('status');
        });

        // Update the new column based on the existing status
        DB::statement("UPDATE borrowings SET status_indonesia = CASE
            WHEN status = 'borrowed' THEN 'dipinjam'
            WHEN status = 'returned' THEN 'dikembalikan'
            WHEN status = 'overdue' THEN 'terlambat'
            ELSE 'dipinjam' END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn('status_indonesia');
        });
    }
};
