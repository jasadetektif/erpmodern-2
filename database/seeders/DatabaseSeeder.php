<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Client;
use App\Models\Supplier;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Asset;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Pengguna Utama & Peran
        $this->command->info('Membuat pengguna utama dan peran...');
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@erp.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
            ]
        );
        $this->call(RolesAndPermissionsSeeder::class);
        $this->command->info('Pengguna dan peran selesai dibuat.');

        // 2. Buat Data Master
        $this->command->info('Membuat data master (Klien, Pemasok, Aset)...');
        Client::factory(15)->create();
        Supplier::factory(25)->create();
        Asset::factory(10)->create();
        $this->command->info('Data master selesai dibuat.');

        // 3. Buat Data Karyawan
        $this->command->info('Membuat data karyawan...');
        // Buat satu karyawan yang terhubung dengan akun admin
        Employee::factory()->create([
            'user_id' => $adminUser->id,
            'position' => 'Direktur'
        ]);
        // Buat 49 karyawan lainnya tanpa akun login
        Employee::factory(49)->create();
        $this->command->info('Data karyawan selesai dibuat.');

        // 4. Buat Data Proyek
        $this->command->info('Membuat data proyek...');
        Project::factory(20)->create()->each(function ($project) {
            // Untuk setiap proyek, tugaskan 1 mandor & beberapa staf
            $mandor = Employee::where('position', 'Mandor')->inRandomOrder()->first();
            if ($mandor) {
                \App\Models\ProjectTeam::create([
                    'project_id' => $project->id,
                    'employee_id' => $mandor->id,
                    'number_of_workers' => rand(5, 15)
                ]);
            }
        });
        $this->command->info('Data proyek selesai dibuat.');

        // 5. Panggil Seeder Lainnya
        $this->command->info('Menjalankan seeder lainnya...');
        $this->call([
            AnnouncementSeeder::class,
            QuoteSeeder::class, // Jika Anda membuat seeder untuk quote
        ]);
        $this->command->info('Semua proses seeding selesai!');
    }
}
