<?php

namespace App\Livewire\Finance\Invoice;

use Livewire\Component;
use App\Models\Invoice;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public $invoices;
    public $purchaseOrders; // PO yang siap ditagih

    // Properti untuk Form
    public $invoiceId;
    public $purchase_order_id, $invoice_number, $invoice_date, $due_date;
    public $items = [];
    public $total_amount = 0;

    public $isModalOpen = false;

    public function mount()
    {
        // Ambil PO yang sudah ada barangnya tapi mungkin belum lunas
        $this->purchaseOrders = PurchaseOrder::whereDoesntHave('invoice')->get();
    }

    public function render()
    {
        $this->invoices = Invoice::with('purchaseOrder.purchaseRequest.project', 'supplier')->latest()->get();
        return view('livewire.finance.invoice.index');
    }

    // Saat PO dipilih di form, muat item-itemnya
    public function updatedPurchaseOrderId($poId)
    {
        $this->items = [];
        if ($poId) {
            $po = PurchaseOrder::with('items')->find($poId);
            foreach ($po->items as $item) {
                $this->items[] = [
                    'purchase_order_item_id' => $item->id,
                    'description' => $item->item_name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->total_price,
                ];
            }
            $this->total_amount = $po->total_amount;
        } else {
            $this->total_amount = 0;
        }
    }

    public function create()
    {
        $this->resetForm();
        $this->purchaseOrders = PurchaseOrder::whereDoesntHave('invoice')->get(); // Muat ulang PO yang tersedia
        $this->openModal();
    }

    public function openModal() { $this->isModalOpen = true; }
    public function closeModal() { $this->isModalOpen = false; }

    private function resetForm()
    {
        $this->invoiceId = null;
        $this->purchase_order_id = '';
        $this->invoice_number = '';
        $this->invoice_date = date('Y-m-d');
        $this->due_date = now()->addDays(30)->format('Y-m-d');
        $this->items = [];
        $this->total_amount = 0;
    }

    public function store()
    {
        $this->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'invoice_number' => 'required|string|max:255|unique:invoices,invoice_number',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
        ]);

        DB::transaction(function () {
            $po = PurchaseOrder::find($this->purchase_order_id);

            $invoice = Invoice::create([
                'invoice_number' => $this->invoice_number,
                'purchase_order_id' => $this->purchase_order_id,
                'supplier_id' => $po->supplier_id,
                'invoice_date' => $this->invoice_date,
                'due_date' => $this->due_date,
                'total_amount' => $this->total_amount,
                'status' => 'Belum Dibayar',
            ]);

            foreach ($this->items as $item) {
                $invoice->items()->create($item);
            }
        });

        session()->flash('message', 'Faktur berhasil dicatat.');
        $this->closeModal();
    }
}
