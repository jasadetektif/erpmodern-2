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
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->string('payment_number')->unique();
        $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
        $table->date('payment_date');
        $table->decimal('amount', 15, 2);
        $table->string('payment_method'); // Contoh: Transfer Bank, Tunai
        $table->foreignId('processed_by_id')->constrained('users')->onDelete('cascade');
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
