<?php
namespace App\Livewire\Master\Labor;

use Livewire\Component;
use App\Models\Labor;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public $labors;
    public $laborId, $name, $unit, $wage;
    public $isModalOpen = false;

    public function render()
    {
        $this->labors = Labor::all();
        return view('livewire.master.labor.index');
    }

    public function create() { $this->resetForm(); $this->openModal(); }
    public function openModal() { $this->isModalOpen = true; }
    public function closeModal() { $this->isModalOpen = false; }
    private function resetForm() {
        $this->laborId = null;
        $this->name = '';
        $this->unit = 'HOK';
        $this->wage = 0;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:labors,name,' . $this->laborId,
            'unit' => 'required|string|max:50',
            'wage' => 'required|numeric|min:0',
        ]);

        Labor::updateOrCreate(['id' => $this->laborId], [
            'name' => $this->name,
            'unit' => $this->unit,
            'wage' => $this->wage,
        ]);

        session()->flash('message', $this->laborId ? 'Data tenaga kerja berhasil diperbarui.' : 'Tenaga kerja baru berhasil ditambahkan.');
        $this->closeModal();
    }

    public function edit(Labor $labor) {
        $this->laborId = $labor->id;
        $this->name = $labor->name;
        $this->unit = $labor->unit;
        $this->wage = $labor->wage;
        $this->openModal();
    }

    public function delete(Labor $labor) {
        $labor->delete();
        session()->flash('message', 'Tenaga kerja berhasil dihapus.');
    }
}
