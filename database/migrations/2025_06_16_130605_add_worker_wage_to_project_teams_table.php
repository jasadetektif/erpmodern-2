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
        $table->decimal('worker_daily_wage', 15, 2)->default(0)->after('number_of_workers');
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
