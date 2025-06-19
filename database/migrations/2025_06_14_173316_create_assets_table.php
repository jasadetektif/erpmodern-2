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
    Schema::create('assets', function (Blueprint $table) {
        $table->id();
        $table->string('asset_code')->unique();
        $table->string('name');
        $table->text('description')->nullable();
        $table->date('purchase_date')->nullable();
        $table->decimal('purchase_price', 15, 2)->default(0);
        $table->string('status')->default('Tersedia'); // Tersedia, Digunakan, Dalam Perbaikan
        $table->foreignId('current_project_id')->nullable()->constrained('projects')->onDelete('set null');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
