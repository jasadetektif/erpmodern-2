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
    Schema::create('goods_receipt_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('goods_receipt_id')->constrained()->onDelete('cascade');
        $table->foreignId('purchase_order_item_id')->constrained()->onDelete('cascade');
        $table->double('received_quantity');
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_receipt_items');
    }
};
