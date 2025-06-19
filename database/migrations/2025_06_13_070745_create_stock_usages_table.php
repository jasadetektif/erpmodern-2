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
    Schema::create('stock_usages', function (Blueprint $table) {
        $table->id();
        $table->string('usage_number')->unique();
        $table->foreignId('project_id')->constrained()->onDelete('cascade');
        $table->foreignId('used_by_id')->constrained('users')->onDelete('cascade');
        $table->date('usage_date');
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_usages');
    }
};
