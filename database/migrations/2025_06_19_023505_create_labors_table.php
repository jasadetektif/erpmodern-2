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
    Schema::create('labors', function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique(); // Contoh: Pekerja, Tukang Batu, Mandor
        $table->string('unit')->default('HOK'); // Hari Orang Kerja
        $table->decimal('wage', 15, 2); // Upah per hari
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labors');
    }
};
