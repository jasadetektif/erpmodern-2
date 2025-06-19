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
    Schema::create('payslips', function (Blueprint $table) {
        $table->id();
        $table->foreignId('payroll_id')->constrained()->onDelete('cascade');
        $table->foreignId('employee_id')->constrained()->onDelete('cascade');
        $table->decimal('gross_salary', 15, 2)->default(0); // Gaji kotor
        $table->decimal('total_deductions', 15, 2)->default(0); // Total potongan
        $table->decimal('net_salary', 15, 2)->default(0); // Gaji bersih
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payslips');
    }
};
