<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Quote;

class QuoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar kutipan yang akan dimasukkan
        $quotes = [
            'The greatest success comes from the freedom to fail.',
            'The only way to do great work is to love what you do.',
            'Start where you are. Use what you have. Do what you can.',
            'Action is the foundational key to all success.',
            'Pastikan #TODO List diselesaikan hari ini, INGAT besok gajian :)'
        ];

        // Looping untuk memasukkan setiap kutipan
        // firstOrCreate akan memastikan tidak ada duplikasi jika seeder dijalankan lagi
        foreach ($quotes as $quoteText) {
            Quote::firstOrCreate(['text' => $quoteText]);
        }
    }
}
