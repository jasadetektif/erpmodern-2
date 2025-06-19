<?php

namespace App\Livewire\Finance\ClientInvoice;

use Livewire\Component;
use App\Models\ClientInvoice;
use App\Models\Project;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public $clientInvoices;
    public $projects;

    // Properti untuk Form
    public $invoiceId;
    public $project_id, $invoice_number, $description, $invoice_date, $due_date, $amount, $status;

    public $isModalOpen = false;

    public function mount()
    {
        $this->projects = Project::all();
    }

    public function render()
    {
        $this->clientInvoices = ClientInvoice::with('project')->latest()->get();
        return view('livewire.finance.client-invoice.index');
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
        $this->invoiceId = null;
        $this->project_id = '';
        $this->invoice_number = 'INV-' . time();
        $this->description = '';
        $this->invoice_date = date('Y-m-d');
        $this->due_date = now()->addDays(30)->format('Y-m-d');
        $this->amount = 0;
        $this->status = 'Terkirim';
    }

    public function store()
    {
        $this->validate([
            'project_id' => 'required|exists:projects,id',
            'invoice_number' => 'required|string|max:255|unique:client_invoices,invoice_number,' . $this->invoiceId,
            'description' => 'required|string|max:255',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'amount' => 'required|numeric|min:1',
            'status' => 'required|string',
        ]);

        ClientInvoice::updateOrCreate(['id' => $this->invoiceId], [
            'project_id' => $this->project_id,
            'invoice_number' => $this->invoice_number,
            'description' => $this->description,
            'invoice_date' => $this->invoice_date,
            'due_date' => $this->due_date,
            'amount' => $this->amount,
            'status' => $this->status,
        ]);

        session()->flash('message', $this->invoiceId ? 'Faktur Klien berhasil diperbarui.' : 'Faktur Klien berhasil dibuat.');
        $this->closeModal();
    }

    public function edit(ClientInvoice $invoice)
    {
        $this->invoiceId = $invoice->id;
        $this->project_id = $invoice->project_id;
        $this->invoice_number = $invoice->invoice_number;
        $this->description = $invoice->description;
        $this->invoice_date = $invoice->invoice_date;
        $this->due_date = $invoice->due_date;
        $this->amount = $invoice->amount;
        $this->status = $invoice->status;
        $this->openModal();
    }

    public function delete(ClientInvoice $invoice)
    {
        $invoice->delete();
        session()->flash('message', 'Faktur Klien berhasil dihapus.');
    }
}