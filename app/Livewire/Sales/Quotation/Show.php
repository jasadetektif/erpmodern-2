<?php

namespace App\Livewire\Sales\Quotation;

use Livewire\Component;
use App\Models\Quotation;
use App\Models\Project; // <-- Import model Project
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Show extends Component
{
    public Quotation $quotation;

    public function mount(Quotation $quotation)
    {
        // Muat relasi yang dibutuhkan agar bisa ditampilkan di view
        $this->quotation = $quotation->load('createdBy', 'items');
    }

    public function render()
    {
        return view('livewire.sales.quotation.show');
    }

    /**
     * Convert an approved quotation into a new project.
     */
    public function convertToProject()
    {
        // Pastikan hanya penawaran yang disetujui yang bisa dikonversi
        if ($this->quotation->status !== 'Disetujui') {
            session()->flash('error', 'Hanya penawaran yang disetujui yang dapat dikonversi menjadi proyek.');
            return;
        }

        // Buat Proyek baru
        $project = Project::create([
            'name' => 'Proyek berdasarkan Penawaran ' . $this->quotation->quotation_number,
            'client' => $this->quotation->client_name,
            'budget' => $this->quotation->total_amount, // Nilai penawaran menjadi anggaran proyek
            'status' => 'Baru',
            'start_date' => now(),
        ]);

        // Update status penawaran agar tidak bisa dikonversi lagi
        $this->quotation->update(['status' => 'Dikonversi menjadi Proyek']);

        session()->flash('message', 'Penawaran berhasil dikonversi menjadi proyek baru.');

        // Arahkan pengguna ke halaman detail proyek yang baru dibuat
        return $this->redirect(route('projects.show', $project->id), navigate: true);
    }
}
