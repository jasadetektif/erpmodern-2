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
    Schema::create('rab_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('rab_id')->constrained()->onDelete('cascade');
        $table->string('category'); // Contoh: Pekerjaan Persiapan, Pekerjaan Struktur
        $table->text('description');
        $table->double('quantity');
        $table->string('unit');
        $table->decimal('unit_price', 15, 2);
        $table->decimal('total_price', 15, 2);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rab_items');
    }
};
