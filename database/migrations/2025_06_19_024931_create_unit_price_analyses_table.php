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
    Schema::create('unit_price_analyses', function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique(); // Contoh: "Analisa 1 m3 Pekerjaan Beton"
        $table->string('unit'); // Contoh: m3, m2, ls
        $table->decimal('total_cost', 15, 2)->default(0); // Total biaya per satuan
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_price_analyses');
    }
};
