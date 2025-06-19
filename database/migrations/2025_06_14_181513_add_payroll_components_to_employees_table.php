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
            // Cek apakah kolom sudah ada sebelum menambahkannya untuk menghindari error
            if (!Schema::hasColumn('employees', 'allowance_transport')) {
                $table->decimal('allowance_transport', 15, 2)->default(0)->after('basic_salary');
            }
            if (!Schema::hasColumn('employees', 'allowance_meal')) {
                $table->decimal('allowance_meal', 15, 2)->default(0)->after('allowance_transport');
            }
            if (!Schema::hasColumn('employees', 'deduction_pph21')) {
                $table->decimal('deduction_pph21', 15, 2)->default(0)->after('allowance_meal');
            }
            if (!Schema::hasColumn('employees', 'deduction_bpjs_tk')) {
                $table->decimal('deduction_bpjs_tk', 15, 2)->default(0)->after('deduction_pph21');
            }
            if (!Schema::hasColumn('employees', 'deduction_bpjs_kesehatan')) {
                $table->decimal('deduction_bpjs_kesehatan', 15, 2)->default(0)->after('deduction_bpjs_tk');
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
            if (Schema::hasColumn('employees', 'allowance_transport')) {
                $table->dropColumn('allowance_transport');
            }
            if (Schema::hasColumn('employees', 'allowance_meal')) {
                $table->dropColumn('allowance_meal');
            }
            if (Schema::hasColumn('employees', 'deduction_pph21')) {
                $table->dropColumn('deduction_pph21');
            }
            if (Schema::hasColumn('employees', 'deduction_bpjs_tk')) {
                $table->dropColumn('deduction_bpjs_tk');
            }
            if (Schema::hasColumn('employees', 'deduction_bpjs_kesehatan')) {
                $table->dropColumn('deduction_bpjs_kesehatan');
            }
        });
    }
};
