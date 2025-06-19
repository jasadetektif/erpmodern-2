<?php

namespace App\Livewire\Inventory\Usage;

use Livewire\Component;
use App\Models\StockUsage;
use App\Models\Project;
use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public $stockUsages;
    public $projects;
    public $inventoryItems = [];

    // Properti untuk Form
    public $usageId;
    public $project_id;
    public $usage_date;
    public $notes;
    public array $items = [];

    public $isModalOpen = false;

    public function mount()
    {
        $this->projects = Project::all();
    }

    public function render()
    {
        $this->stockUsages = StockUsage::with('project', 'usedBy')->latest()->get();
        return view('livewire.inventory.usage.index');
    }

    // Saat proyek dipilih di form, muat item inventaris yang tersedia untuk proyek itu
    public function updatedProjectId($projectId)
    {
        if ($projectId) {
            $this->inventoryItems = Inventory::where('project_id', $projectId)->where('stock_quantity', '>', 0)->get();
        } else {
            $this->inventoryItems = [];
        }
        $this->items = []; // Reset item setiap kali proyek berubah
    }

    public function create()
    {
        $this->resetForm();
        $this->addItem();
        $this->openModal();
    }

    public function openModal() { $this->isModalOpen = true; }
    public function closeModal() { $this->isModalOpen = false; }

    private function resetForm()
    {
        $this->usageId = null;
        $this->project_id = '';
        $this->usage_date = date('Y-m-d');
        $this->notes = '';
        $this->items = [];
        $this->inventoryItems = [];
    }

    public function addItem()
    {
        $this->items[] = ['inventory_id' => '', 'used_quantity' => 1, 'max_quantity' => 0, 'unit' => ''];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }
    
    // Perbaikan: Logika hanya berjalan saat 'inventory_id' diubah
    public function updatedItems($value, $path)
    {
        // $path akan berupa '0.inventory_id' atau '0.used_quantity'
        $parts = explode('.', $path);
        $index = $parts[0];
        $property = $parts[1];

        // Hanya jalankan logika ini jika properti 'inventory_id' yang berubah.
        if ($property === 'inventory_id') {
            $inventoryId = $value;

            if ($inventoryId) {
                $inventory = Inventory::find($inventoryId);
                if ($inventory) {
                    $this->items[$index]['max_quantity'] = $inventory->stock_quantity;
                    $this->items[$index]['unit'] = $inventory->unit;
                }
            } else {
                // Reset jika pengguna memilih "Select Item" lagi
                $this->items[$index]['max_quantity'] = 0;
                $this->items[$index]['unit'] = '';
            }
        }
    }

    public function store()
    {
        $this->validate([
            'project_id' => 'required|exists:projects,id',
            'usage_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.inventory_id' => 'required|exists:inventories,id',
            // Perbaikan: Validasi kustom yang lebih aman
            'items.*.used_quantity' => ['required', 'numeric', 'min:0.1', function ($attribute, $value, $fail) {
                $index = explode('.', $attribute)[1];
                if (empty($this->items[$index]['inventory_id'])) {
                    return; // Lewati validasi jika item belum dipilih
                }
                $inventory = Inventory::find($this->items[$index]['inventory_id']);
                if (!$inventory) {
                    $fail('Item inventaris tidak valid.');
                    return;
                }
                if ($value > $inventory->stock_quantity) {
                    $fail(__('The used quantity cannot exceed the available stock.'));
                }
            }],
        ]);

        DB::transaction(function () {
            $usage = StockUsage::create([
                'usage_number' => 'SU-' . time(),
                'project_id' => $this->project_id,
                'used_by_id' => Auth::id(),
                'usage_date' => $this->usage_date,
                'notes' => $this->notes,
            ]);

            foreach ($this->items as $item) {
                $usage->items()->create($item);

                // Kurangi stok dari inventaris
                $inventory = Inventory::find($item['inventory_id']);
                $inventory->decrement('stock_quantity', $item['used_quantity']);
            }
        });

        session()->flash('message', 'Penggunaan stok berhasil dicatat.');
        $this->closeModal();
    }
}
