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
    Schema::table('tasks', function (Blueprint $table) {
        // Kita ganti nama 'due_date' menjadi 'end_date' agar lebih jelas
        $table->renameColumn('due_date', 'end_date');
        // Tambahkan kolom start_date setelah description
        $table->date('start_date')->nullable()->after('description');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            //
        });
    }
};
