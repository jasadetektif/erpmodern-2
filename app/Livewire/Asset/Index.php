<?php

namespace App\Livewire\Asset;

use Livewire\Component;
use App\Models\Asset;
use App\Models\Project;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public $assets;
    public $projects;

    // Properti untuk Form
    public $assetId;
    public $asset_code, $name, $description, $purchase_date, $purchase_price, $status, $current_project_id;

    public $isModalOpen = false;

    public function mount()
    {
        $this->projects = Project::all();
    }

    public function render()
    {
        $this->assets = Asset::with('project')->latest()->get();
        return view('livewire.asset.index');
    }

    public function create()
    {
        $this->resetForm();
        $this->openModal();
    }

    public function openModal() { $this->isModalOpen = true; }
    public function closeModal() { $this->isModalOpen = false; }

    private function resetForm()
    {
        $this->assetId = null;
        $this->asset_code = 'AST-' . time();
        $this->name = '';
        $this->description = '';
        $this->purchase_date = date('Y-m-d');
        $this->purchase_price = 0;
        $this->status = 'Tersedia';
        $this->current_project_id = null;
    }

    public function store()
    {
        $this->validate([
            'asset_code' => 'required|string|max:255|unique:assets,asset_code,' . $this->assetId,
            'name' => 'required|string|max:255',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'required|numeric|min:0',
            'status' => 'required|string',
            'current_project_id' => 'nullable|exists:projects,id',
        ]);

        Asset::updateOrCreate(['id' => $this->assetId], [
            'asset_code' => $this->asset_code,
            'name' => $this->name,
            'description' => $this->description,
            'purchase_date' => $this->purchase_date,
            'purchase_price' => $this->purchase_price,
            'status' => $this->status,
            'current_project_id' => $this->current_project_id,
        ]);

        session()->flash('message', $this->assetId ? 'Data aset berhasil diperbarui.' : 'Aset baru berhasil ditambahkan.');
        $this->closeModal();
    }

    public function edit(Asset $asset)
    {
        $this->assetId = $asset->id;
        $this->asset_code = $asset->asset_code;
        $this->name = $asset->name;
        $this->description = $asset->description;
        $this->purchase_date = $asset->purchase_date;
        $this->purchase_price = $asset->purchase_price;
        $this->status = $asset->status;
        $this->current_project_id = $asset->current_project_id;
        $this->openModal();
    }

    public function delete(Asset $asset)
    {
        $asset->delete();
        session()->flash('message', 'Aset berhasil dihapus.');
    }
}