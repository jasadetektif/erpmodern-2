<?php
namespace App\Livewire\Master\Material;

use Livewire\Component;
use App\Models\Material;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public $materials;
    public $materialId, $name, $unit, $price;
    public $isModalOpen = false;

    public function render()
    {
        $this->materials = Material::all();
        return view('livewire.master.material.index');
    }

    public function create() { $this->resetForm(); $this->openModal(); }
    public function openModal() { $this->isModalOpen = true; }
    public function closeModal() { $this->isModalOpen = false; }
    private function resetForm() {
        $this->materialId = null;
        $this->name = '';
        $this->unit = '';
        $this->price = 0;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:materials,name,' . $this->materialId,
            'unit' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
        ]);

        Material::updateOrCreate(['id' => $this->materialId], [
            'name' => $this->name,
            'unit' => $this->unit,
            'price' => $this->price,
        ]);

        session()->flash('message', $this->materialId ? 'Data material berhasil diperbarui.' : 'Material baru berhasil ditambahkan.');
        $this->closeModal();
    }

    public function edit(Material $material) {
        $this->materialId = $material->id;
        $this->name = $material->name;
        $this->unit = $material->unit;
        $this->price = $material->price;
        $this->openModal();
    }

    public function delete(Material $material) {
        $material->delete();
        session()->flash('message', 'Material berhasil dihapus.');
    }
}
