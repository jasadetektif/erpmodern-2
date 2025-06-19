<?php

namespace App\Livewire\Asset;

use Livewire\Component;
use App\Models\Asset;
use App\Models\AssetMaintenance;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Show extends Component
{
    public Asset $asset;

    // Properti untuk form maintenance
    public $maintenanceId;
    public $maintenance_date, $maintenance_type, $maintenance_description, $maintenance_cost;
    public $isModalOpen = false;

    public function mount(Asset $asset)
    {
        // Muat relasi project dan maintenances
        $this->asset = $asset->load('project', 'maintenances.conductedBy');
    }

    public function render()
    {
        return view('livewire.asset.show');
    }

    // --- Logika untuk Maintenance ---
    public function openModal() { $this->isModalOpen = true; }
    public function closeModal() { $this->isModalOpen = false; }

    public function createMaintenance()
    {
        $this->resetMaintenanceForm();
        $this->openModal();
    }

    private function resetMaintenanceForm()
    {
        $this->maintenanceId = null;
        $this->maintenance_date = date('Y-m-d');
        $this->maintenance_type = 'Servis Rutin';
        $this->maintenance_description = '';
        $this->maintenance_cost = 0;
    }

    public function storeMaintenance()
    {
        $this->validate([
            'maintenance_date' => 'required|date',
            'maintenance_type' => 'required|string|max:255',
            'maintenance_description' => 'required|string',
            'maintenance_cost' => 'required|numeric|min:0',
        ]);

        $this->asset->maintenances()->create([
            'maintenance_date' => $this->maintenance_date,
            'type' => $this->maintenance_type,
            'description' => $this->maintenance_description,
            'cost' => $this->maintenance_cost,
            'conducted_by_id' => Auth::id(),
        ]);

        session()->flash('message', 'Catatan pemeliharaan berhasil ditambahkan.');
        $this->asset->load('maintenances.conductedBy'); // Muat ulang data
        $this->closeModal();
    }
}
