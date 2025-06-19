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
    Schema::create('payrolls', function (Blueprint $table) {
        $table->id();
        $table->string('payroll_period'); // Contoh: "Juni 2025"
        $table->date('start_date');
        $table->date('end_date');
        $table->string('status')->default('Draft'); // Draft, Diproses, Selesai
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
