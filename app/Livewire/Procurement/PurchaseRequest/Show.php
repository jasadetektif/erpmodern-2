<?php

namespace App\Livewire\Procurement\PurchaseRequest;

use Livewire\Component;
use App\Models\PurchaseRequest;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Show extends Component
{
    public PurchaseRequest $purchaseRequest;

    public function mount(PurchaseRequest $purchaseRequest)
    {
        // Muat relasi yang dibutuhkan agar bisa ditampilkan di view
        $this->purchaseRequest = $purchaseRequest->load('project', 'requester', 'items');
    }

    public function render()
    {
        return view('livewire.procurement.purchase-request.show');
    }
}
