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
    Schema::table('project_teams', function (Blueprint $table) {
        $table->enum('payment_type', ['harian', 'borongan'])->default('harian')->after('number_of_workers');
        $table->decimal('lump_sum_value', 15, 2)->default(0)->comment('Nilai borongan')->after('payment_type');
        $table->unsignedTinyInteger('work_progress')->default(0)->comment('Progres pekerjaan dalam persen')->after('lump_sum_value');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_teams', function (Blueprint $table) {
            //
        });
    }
};
