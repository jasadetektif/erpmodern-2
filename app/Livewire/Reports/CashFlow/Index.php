<?php

namespace App\Livewire\Reports\CashFlow;

use Livewire\Component;
use App\Models\Payment;
use App\Models\ClientPayment;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public Collection $transactions;
    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
        $this->loadTransactions();
    }

    public function loadTransactions()
    {
        $cashIn = ClientPayment::with('clientInvoice.project')
            ->whereBetween('payment_date', [$this->startDate, $this->endDate])
            ->get()
            ->map(function ($payment) {
                return (object)[
                    'date' => $payment->payment_date,
                    'description' => 'Penerimaan dari ' . $payment->clientInvoice->project->client . ' (Inv: ' . $payment->clientInvoice->invoice_number . ')',
                    'cash_in' => $payment->amount,
                    'cash_out' => 0,
                ];
            });

        $cashOut = Payment::with('invoice.supplier')
            ->whereBetween('payment_date', [$this->startDate, $this->endDate])
            ->get()
            ->map(function ($payment) {
                return (object)[
                    'date' => $payment->payment_date,
                    'description' => 'Pembayaran ke ' . $payment->invoice->supplier->name . ' (Inv: ' . $payment->invoice->invoice_number . ')',
                    'cash_in' => 0,
                    'cash_out' => $payment->amount,
                ];
            });

        $this->transactions = $cashIn->concat($cashOut)->sortBy('date');
    }
    
    public function updated($property)
    {
        if ($property === 'startDate' || $property === 'endDate') {
            $this->loadTransactions();
        }
    }

    public function render()
    {
        return view('livewire.reports.cash-flow.index');
    }
}