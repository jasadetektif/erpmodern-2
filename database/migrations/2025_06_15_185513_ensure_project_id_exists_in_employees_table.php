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
        Schema::table('employees', function (Blueprint $table) {
            // Hanya tambahkan kolom jika belum ada
            if (!Schema::hasColumn('employees', 'project_id')) {
                $table->foreignId('project_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Logika untuk menghapus kolom jika migrasi di-rollback
            if (Schema::hasColumn('employees', 'project_id')) {
                // Hapus foreign key constraint sebelum menghapus kolom
                // Nama constraint bisa bervariasi, sesuaikan jika perlu
                // Format umum: {table}_{column}_foreign
                $table->dropForeign(['project_id']);
                $table->dropColumn('project_id');
            }
        });
    }
};
