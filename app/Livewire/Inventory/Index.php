<?php

namespace App\Livewire\Inventory;

use Livewire\Component;
use App\Models\Inventory;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public $inventoryItems;

    public function render()
    {
        $this->inventoryItems = Inventory::with('project')->get();
        return view('livewire.inventory.index');
    }
}
