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
        $table->decimal('allowance_transport', 15, 2)->default(0)->after('basic_salary');
        $table->decimal('allowance_meal', 15, 2)->default(0)->after('allowance_transport');
        $table->decimal('deduction_pph21', 15, 2)->default(0)->after('allowance_meal');
        $table->decimal('deduction_bpjs_tk', 15, 2)->default(0)->after('deduction_pph21');
        $table->decimal('deduction_bpjs_kesehatan', 15, 2)->default(0)->after('deduction_bpjs_tk');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            //
        });
    }
};
