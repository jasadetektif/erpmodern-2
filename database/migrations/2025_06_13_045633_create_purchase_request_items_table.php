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
        Schema::create('purchase_request_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_request_id')->constrained()->onDelete('cascade');
            $table->string('item_name');
            $table->double('quantity');
            $table->string('unit'); // Contoh: Pcs, M2, M3, Ltr
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
};
