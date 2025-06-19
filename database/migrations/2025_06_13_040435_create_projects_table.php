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
    Schema::create('projects', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('client')->nullable();
        $table->date('start_date')->nullable();
        $table->date('end_date')->nullable();
        $table->decimal('budget', 15, 2)->nullable();
        $table->string('status')->default('Baru'); // Contoh status: Baru, Berjalan, Selesai
        $table->timestamps();
    });
}
};
