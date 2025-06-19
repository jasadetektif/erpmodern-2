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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->foreignId('purchase_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_by_id')->constrained('users')->onDelete('cascade');
            $table->date('order_date');
            $table->decimal('total_amount', 15, 2);
            $table->string('status')->default('Dikirim'); // Dikirim, Sebagian Diterima, Diterima Penuh, Dibatalkan
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
};
