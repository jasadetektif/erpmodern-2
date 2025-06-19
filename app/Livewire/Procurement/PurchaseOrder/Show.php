<?php

namespace App\Livewire\Procurement\PurchaseOrder;

use Livewire\Component;
use App\Models\PurchaseOrder;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Show extends Component
{
    public PurchaseOrder $purchaseOrder;

    public function mount(PurchaseOrder $purchaseOrder)
    {
        // Muat semua relasi yang dibutuhkan
        $this->purchaseOrder = $purchaseOrder->load(
            'supplier',
            'orderBy',
            'items',
            'purchaseRequest.project'
        );
    }

    public function render()
    {
        return view('livewire.procurement.purchase-order.show');
    }
}
