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
    Schema::create('employees', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
        $table->string('employee_id_number')->unique();
        $table->string('position'); // Jabatan
        $table->date('join_date');
        $table->string('phone')->nullable();
        $table->text('address')->nullable();
        $table->string('status')->default('Aktif'); // Aktif, Resign, Cuti
        $table->decimal('basic_salary', 15, 2)->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
