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
    Schema::create('analysis_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('unit_price_analysis_id')->constrained()->onDelete('cascade');
        $table->morphs('analyzable'); // Bisa Material atau Labor
        $table->double('coefficient'); // Koefisien/faktor pengali
        $table->timestamps();
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analysis_items');
    }
};
