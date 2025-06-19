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
    Schema::create('attendances', function (Blueprint $table) {
        $table->id();
        $table->foreignId('employee_id')->constrained()->onDelete('cascade');
        $table->foreignId('project_id')->constrained()->onDelete('cascade');
        $table->date('date');
        $table->enum('status', ['Hadir', 'Sakit', 'Izin', 'Alpha']);
        $table->text('notes')->nullable();
        $table->timestamps();

        // Mencegah duplikasi absensi untuk karyawan yang sama di tanggal yang sama
        $table->unique(['employee_id', 'date']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
