<?php
namespace App\Livewire\Finance\ClientPayment;
use Livewire\Component;
use App\Models\ClientPayment;
use Livewire\Attributes\Layout;
#[Layout('components.layouts.app')]
class Show extends Component
{
    public ClientPayment $clientPayment;
    public function mount(ClientPayment $clientPayment)
    {
        $this->clientPayment = $clientPayment->load('clientInvoice.project', 'receivedBy');
    }
    public function render()
    {
        return view('livewire.finance.client-payment.show');
    }
}
