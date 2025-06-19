<?php

namespace App\Livewire\Procurement\PurchaseOrder;

use Livewire\Component;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public $purchaseOrders;
    public $purchaseRequests; // PR yang disetujui
    public $suppliers;

    // Properti untuk Form
    public $poId;
    public $purchase_request_id;
    public $supplier_id;
    public $order_date;
    public $notes;
    public array $items = [];
    public $total_amount = 0;

    public $isModalOpen = false;

    // Fungsi ini dipanggil saat komponen pertama kali dimuat
    public function mount()
    {
        // Untuk form, kita hanya butuh PR yang disetujui & belum diproses dan semua supplier
        $this->purchaseRequests = PurchaseRequest::where('status', 'Disetujui')->get();
        $this->suppliers = Supplier::all();
    }

    public function render()
    {
        // Untuk tabel, kita tampilkan semua PO yang sudah dibuat
        $this->purchaseOrders = PurchaseOrder::with('purchaseRequest.project', 'supplier')->latest()->get();
        return view('livewire.procurement.purchase-order.index');
    }

    // Saat PR dipilih di form, load item-itemnya
    public function updatedPurchaseRequestId($prId)
    {
        $this->items = []; // Kosongkan item setiap kali PR baru dipilih
        if ($prId) {
            $pr = PurchaseRequest::with('items')->find($prId);
            foreach ($pr->items as $item) {
                $this->items[] = [
                    'purchase_request_item_id' => $item->id,
                    'item_name' => $item->item_name,
                    'quantity' => $item->quantity,
                    'unit' => $item->unit,
                    'price' => 0,
                    'total_price' => 0,
                ];
            }
        }
        $this->calculateTotal();
    }

    // Hitung total harga setiap kali harga atau kuantitas di dalam array items berubah
    public function updatedItems()
    {
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $total = 0;
        foreach ($this->items as $index => $item) {
            $quantity = is_numeric($item['quantity'] ?? 0) ? $item['quantity'] : 0;
            $price = is_numeric($item['price'] ?? 0) ? $item['price'] : 0;
            $this->items[$index]['total_price'] = $quantity * $price;
            $total += $this->items[$index]['total_price'];
        }
        $this->total_amount = $total;
    }

    public function create()
    {
        $this->resetForm();
        // Muat ulang data PR yang disetujui setiap kali modal dibuka
        $this->purchaseRequests = PurchaseRequest::where('status', 'Disetujui')->get();
        $this->openModal();
    }

    public function openModal() { $this->isModalOpen = true; }
    public function closeModal() { $this->isModalOpen = false; }

    private function resetForm()
    {
        $this->poId = null;
        $this->purchase_request_id = '';
        $this->supplier_id = '';
        $this->order_date = date('Y-m-d');
        $this->notes = '';
        $this->items = [];
        $this->total_amount = 0;
    }

    public function store()
    {
        $this->validate([
            'purchase_request_id' => 'required|exists:purchase_requests,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () {
            $po = PurchaseOrder::create([
                'po_number' => 'PO-' . time(),
                'purchase_request_id' => $this->purchase_request_id,
                'supplier_id' => $this->supplier_id,
                'order_by_id' => Auth::id(),
                'order_date' => $this->order_date,
                'total_amount' => $this->total_amount,
                'status' => 'Dikirim',
                'notes' => $this->notes,
            ]);

            foreach ($this->items as $item) {
                $po->items()->create($item);
            }

            // Update status PR menjadi "PO Dibuat" agar tidak bisa dipilih lagi
            PurchaseRequest::find($this->purchase_request_id)->update(['status' => 'PO Dibuat']);
        });

        session()->flash('message', 'Purchase Order berhasil dibuat.');
        $this->closeModal();
    }
}
