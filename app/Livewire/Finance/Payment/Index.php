<?php

namespace App\Livewire\Finance\Payment;

use Livewire\Component;
use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public $payments;
    public $unpaidInvoices;

    // Properti untuk Form
    public $paymentId;
    public $invoice_id, $payment_date, $amount, $payment_method, $notes;

    public $isModalOpen = false;

    public function mount()
    {
        // Ambil faktur yang belum lunas untuk ditampilkan di form
        $this->unpaidInvoices = Invoice::where('status', 'Belum Dibayar')->get();
    }

    public function render()
    {
        // Untuk tabel, tampilkan semua pembayaran yang sudah dibuat
        // Perbaikan: Mengubah relasi untuk memuat data proyek dengan benar
        $this->payments = Payment::with('invoice.purchaseOrder.purchaseRequest.project', 'invoice.supplier', 'processedBy')->latest()->get();
        return view('livewire.finance.payment.index');
    }

    // Saat faktur dipilih di form, isi otomatis jumlah pembayarannya
    public function updatedInvoiceId($invoiceId)
    {
        if ($invoiceId) {
            $invoice = Invoice::find($invoiceId);
            $this->amount = $invoice->total_amount;
        } else {
            $this->amount = 0;
        }
    }

    public function create()
    {
        $this->resetForm();
        // Muat ulang faktur yang belum lunas setiap kali modal dibuka
        $this->unpaidInvoices = Invoice::where('status', 'Belum Dibayar')->get();
        $this->openModal();
    }

    public function openModal() { $this->isModalOpen = true; }
    public function closeModal() { $this->isModalOpen = false; }

    private function resetForm()
    {
        $this->paymentId = null;
        $this->invoice_id = '';
        $this->payment_date = date('Y-m-d');
        $this->amount = 0;
        $this->payment_method = 'Transfer Bank';
        $this->notes = '';
    }

    public function store()
    {
        $this->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string|max:255',
        ]);

        DB::transaction(function () {
            // 1. Buat catatan pembayaran
            Payment::create([
                'payment_number' => 'PAY-' . time(),
                'invoice_id' => $this->invoice_id,
                'payment_date' => $this->payment_date,
                'amount' => $this->amount,
                'payment_method' => $this->payment_method,
                'processed_by_id' => Auth::id(),
                'notes' => $this->notes,
            ]);

            // 2. Update status faktur terkait menjadi 'Lunas'
            $invoice = Invoice::find($this->invoice_id);
            $invoice->update(['status' => 'Lunas']);
        });

        session()->flash('message', 'Pembayaran berhasil dicatat.');
        $this->closeModal();
    }
}
