<?php

namespace App\Livewire\Procurement\GoodsReceipt;

use Livewire\Component;
use App\Models\GoodsReceipt;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Show extends Component
{
    public GoodsReceipt $goodsReceipt;

    public function mount(GoodsReceipt $goodsReceipt)
    {
        // Muat semua relasi yang dibutuhkan
        $this->goodsReceipt = $goodsReceipt->load(
            'receivedBy',
            'items.purchaseOrderItem',
            'purchaseOrder.purchaseRequest.project'
        );
    }

    public function render()
    {
        return view('livewire.procurement.goods-receipt.show');
    }
}
