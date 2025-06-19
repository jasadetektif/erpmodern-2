<?php

namespace App\Livewire\Finance\ClientPayment;

use Livewire\Component;
use App\Models\ClientPayment;
use App\Models\ClientInvoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public $clientPayments;
    public $unpaidInvoices;

    public $paymentId;
    public $client_invoice_id, $payment_date, $amount, $payment_method, $notes;
    public $isModalOpen = false;

    public function mount()
    {
        // Perbaikan: Eager load relasi project untuk dropdown
        $this->unpaidInvoices = ClientInvoice::with('project')->where('status', 'Terkirim')->get();
    }

    public function render()
    {
        $this->clientPayments = ClientPayment::with('clientInvoice.project', 'receivedBy')->latest()->get();
        return view('livewire.finance.client-payment.index');
    }

    public function updatedClientInvoiceId($invoiceId)
    {
        $this->amount = $invoiceId ? ClientInvoice::find($invoiceId)->amount : 0;
    }

    public function create()
    {
        $this->resetForm();
        // Perbaikan: Eager load relasi project saat modal dibuka
        $this->unpaidInvoices = ClientInvoice::with('project')->where('status', 'Terkirim')->get();
        $this->openModal();
    }

    public function openModal() { $this->isModalOpen = true; }
    public function closeModal() { $this->isModalOpen = false; }

    private function resetForm()
    {
        $this->paymentId = null;
        $this->client_invoice_id = '';
        $this->payment_date = date('Y-m-d');
        $this->amount = 0;
        $this->payment_method = 'Transfer Bank';
        $this->notes = '';
    }

    public function store()
    {
        $this->validate([
            'client_invoice_id' => 'required|exists:client_invoices,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string|max:255',
        ]);

        DB::transaction(function () {
            ClientPayment::create([
                'payment_number' => 'RCPT-' . time(),
                'client_invoice_id' => $this->client_invoice_id,
                'payment_date' => $this->payment_date,
                'amount' => $this->amount,
                'payment_method' => $this->payment_method,
                'received_by_id' => Auth::id(),
                'notes' => $this->notes,
            ]);

            ClientInvoice::find($this->client_invoice_id)->update(['status' => 'Lunas']);
        });

        session()->flash('message', 'Penerimaan pembayaran berhasil dicatat.');
        $this->closeModal();
    }
}
