<?php

namespace App\Livewire\Procurement\GoodsReceipt;

use Livewire\Component;
use App\Models\GoodsReceipt;
use App\Models\PurchaseOrder;
use App\Models\GoodsReceiptItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public $goodsReceipts;
    public $purchaseOrders; // PO yang siap diterima

    // Properti untuk Form
    public $grId;
    public $purchase_order_id;
    public $receipt_date;
    public $notes;
    public array $items = [];

    public $isModalOpen = false;

    public function mount()
    {
        // Untuk form, kita hanya butuh PO yang statusnya relevan
        $this->purchaseOrders = PurchaseOrder::whereIn('status', ['Dikirim', 'Sebagian Diterima'])->get();
    }

    public function render()
    {
        // Untuk tabel, tampilkan semua GR yang sudah dibuat
        $this->goodsReceipts = GoodsReceipt::with('purchaseOrder.purchaseRequest.project', 'receivedBy')->latest()->get();
        return view('livewire.procurement.goods-receipt.index');
    }

    // Saat PO dipilih di form, muat item-itemnya
    public function updatedPurchaseOrderId($poId)
    {
        $this->items = [];
        if ($poId) {
            $po = PurchaseOrder::with('items')->find($poId);
            foreach ($po->items as $item) {
                // Hitung jumlah yang sudah diterima sebelumnya untuk item ini
                $previouslyReceived = GoodsReceiptItem::where('purchase_order_item_id', $item->id)->sum('received_quantity');
                $outstandingQty = $item->quantity - $previouslyReceived;

                $this->items[] = [
                    'purchase_order_item_id' => $item->id,
                    'item_name' => $item->item_name,
                    'ordered_quantity' => $item->quantity,
                    'unit' => $item->unit,
                    'previously_received' => $previouslyReceived,
                    'received_quantity' => $outstandingQty, // Otomatis isi dengan sisa barang
                    'notes' => ''
                ];
            }
        }
    }

    public function create()
    {
        $this->resetForm();
        // Muat ulang PO yang relevan setiap kali modal dibuka
        $this->purchaseOrders = PurchaseOrder::whereIn('status', ['Dikirim', 'Sebagian Diterima'])->get();
        $this->openModal();
    }

    public function openModal() { $this->isModalOpen = true; }
    public function closeModal() { $this->isModalOpen = false; }

    private function resetForm()
    {
        $this->grId = null;
        $this->purchase_order_id = '';
        $this->receipt_date = date('Y-m-d');
        $this->notes = '';
        $this->items = [];
    }

    public function store()
    {
        $this->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'receipt_date' => 'required|date',
            'items.*.received_quantity' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () {
            // Buat dokumen GR utama
            $gr = GoodsReceipt::create([
                'gr_number' => 'GR-' . time(),
                'purchase_order_id' => $this->purchase_order_id,
                'received_by_id' => Auth::id(),
                'receipt_date' => $this->receipt_date,
                'status' => 'Sesuai', // Logika status bisa dikembangkan lebih lanjut
                'notes' => $this->notes,
            ]);

            $po = PurchaseOrder::with('items', 'purchaseRequest')->find($this->purchase_order_id);

            // Simpan item-item yang diterima
            foreach ($this->items as $item) {
                if ($item['received_quantity'] > 0) {
                    $gr->items()->create([
                        'purchase_order_item_id' => $item['purchase_order_item_id'],
                        'received_quantity' => $item['received_quantity'],
                        'notes' => $item['notes'],
                    ]);

                    // --- LOGIKA INTEGRASI INVENTARIS DIMULAI DI SINI ---
                    $inventory = \App\Models\Inventory::firstOrCreate(
                        [
                            'project_id' => $po->purchaseRequest->project_id,
                            'item_name' => $item['item_name'],
                        ],
                        [
                            'unit' => $item['unit'],
                            'stock_quantity' => 0,
                        ]
                    );

                    // Tambahkan stok
                    $inventory->increment('stock_quantity', $item['received_quantity']);
                    // --- LOGIKA INTEGRASI INVENTARIS SELESAI ---
                }
            }

            // Update status PO
            $totalOrdered = $po->items->sum('quantity');
            $totalReceived = GoodsReceiptItem::whereIn('purchase_order_item_id', $po->items->pluck('id'))->sum('received_quantity');

            if ($totalReceived >= $totalOrdered) {
                $po->update(['status' => 'Diterima Penuh']);
            } else {
                $po->update(['status' => 'Sebagian Diterima']);
            }
        });

        session()->flash('message', 'Penerimaan barang berhasil dicatat.');
        $this->closeModal();
    }
}
