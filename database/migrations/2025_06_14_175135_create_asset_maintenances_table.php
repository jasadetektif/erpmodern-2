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
    Schema::create('asset_maintenances', function (Blueprint $table) {
        $table->id();
        $table->foreignId('asset_id')->constrained()->onDelete('cascade');
        $table->date('maintenance_date');
        $table->string('type'); // Contoh: Servis Rutin, Ganti Oli, Perbaikan Darurat
        $table->text('description');
        $table->decimal('cost', 15, 2)->default(0);
        $table->foreignId('conducted_by_id')->nullable()->constrained('users')->onDelete('set null');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_maintenances');
    }
};
