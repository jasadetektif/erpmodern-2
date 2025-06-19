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
    Schema::create('quotations', function (Blueprint $table) {
        $table->id();
        $table->string('quotation_number')->unique();
        $table->foreignId('created_by_id')->constrained('users')->onDelete('cascade');
        $table->string('client_name');
        $table->string('client_contact')->nullable();
        $table->date('quotation_date');
        $table->date('valid_until_date');
        $table->decimal('total_amount', 15, 2)->default(0);
        $table->string('status')->default('Draft'); // Draft, Terkirim, Disetujui, Ditolak
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
