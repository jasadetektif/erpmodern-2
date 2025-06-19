<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Announcement;
class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        Announcement::firstOrCreate(
            ['title' => 'Rapat Proyek Besar Pekan Depan'],
            [
                'content' => 'Akan diadakan rapat koordinasi untuk semua Manajer Proyek pada hari Senin, 23 Juni 2025 pukul 09:00 WIB. Mohon kehadirannya.',
                'user_id' => 1, // Diasumsikan user ID 1 adalah Direktur
                'is_active' => true
            ]
        );
    }
}