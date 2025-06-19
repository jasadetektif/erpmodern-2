<?php

namespace App\Livewire\Finance\ClientInvoice;

use Livewire\Component;
use App\Models\ClientInvoice;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Show extends Component
{
    public ClientInvoice $clientInvoice;

    public function mount(ClientInvoice $clientInvoice)
    {
        // Muat relasi yang dibutuhkan agar bisa ditampilkan di view
        $this->clientInvoice = $clientInvoice->load('project');
    }

    public function render()
    {
        return view('livewire.finance.client-invoice.show');
    }
}
