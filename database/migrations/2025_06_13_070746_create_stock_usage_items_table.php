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
    Schema::create('stock_usage_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('stock_usage_id')->constrained()->onDelete('cascade');
        $table->foreignId('inventory_id')->constrained()->onDelete('cascade');
        $table->double('used_quantity');
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_usage_items');
    }
};
