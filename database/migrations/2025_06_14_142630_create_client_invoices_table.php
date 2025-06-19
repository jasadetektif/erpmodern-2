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
    Schema::create('client_invoices', function (Blueprint $table) {
        $table->id();
        $table->string('invoice_number')->unique();
        $table->foreignId('project_id')->constrained()->onDelete('cascade');
        $table->string('description'); // Contoh: "Pembayaran Termin 1 - 30%"
        $table->date('invoice_date');
        $table->date('due_date');
        $table->decimal('amount', 15, 2);
        $table->string('status')->default('Terkirim'); // Terkirim, Lunas, Telat
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_invoices');
    }
};
