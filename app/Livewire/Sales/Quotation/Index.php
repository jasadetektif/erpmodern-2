<?php

namespace App\Livewire\Sales\Quotation;

use Livewire\Component;
use App\Models\Quotation;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public $quotations;
    public $clients;

    // Properti untuk Form
    public $quotationId;
    public $client_id;
    public $quotation_date, $valid_until_date, $notes, $status;
    public array $items = [];
    public $total_amount = 0;

    public $isModalOpen = false;

    public function mount()
    {
        $this->clients = Client::all();
    }

    public function render()
    {
        $this->quotations = Quotation::with('createdBy', 'client')->latest()->get();
        return view('livewire.sales.quotation.index');
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
        $this->quotationId = null;
        $this->client_id = '';
        $this->quotation_date = date('Y-m-d');
        $this->valid_until_date = now()->addDays(14)->format('Y-m-d');
        $this->notes = '';
        $this->status = 'Draft';
        $this->items = [];
        $this->total_amount = 0;
    }

    public function addItem()
    {
        $this->items[] = ['description' => '', 'quantity' => 1, 'unit' => 'Pcs', 'price' => 0, 'total' => 0];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->calculateTotal();
    }

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
            $this->items[$index]['total'] = $quantity * $price;
            $total += $this->items[$index]['total'];
        }
        $this->total_amount = $total;
    }

    public function store()
    {
        $this->validate([
            'client_id' => 'required|exists:clients,id',
            'quotation_date' => 'required|date',
            'valid_until_date' => 'required|date|after_or_equal:quotation_date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit' => 'required|string|max:50',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () {
            $client = Client::find($this->client_id);

            $quotation = Quotation::updateOrCreate(['id' => $this->quotationId], [
                'quotation_number' => $this->quotationId ? Quotation::find($this->quotationId)->quotation_number : 'QUO-' . time(),
                'created_by_id' => Auth::id(),
                'client_id' => $this->client_id,
                'client_name' => $client->client_name,
                'client_contact' => $client->contact_person,
                'quotation_date' => $this->quotation_date,
                'valid_until_date' => $this->valid_until_date,
                'total_amount' => $this->total_amount,
                'status' => $this->status,
                'notes' => $this->notes,
            ]);

            $quotation->items()->delete();

            foreach ($this->items as $item) {
                $quotation->items()->create($item);
            }
        });

        session()->flash('message', $this->quotationId ? 'Penawaran berhasil diperbarui.' : 'Penawaran berhasil dibuat.');
        $this->closeModal();
    }
    
    public function edit(Quotation $quotation)
    {
        $this->quotationId = $quotation->id;
        $this->client_id = $quotation->client_id;
        $this->quotation_date = $quotation->quotation_date;
        $this->valid_until_date = $quotation->valid_until_date;
        $this->notes = $quotation->notes;
        $this->status = $quotation->status;
        $this->items = $quotation->items->toArray();
        $this->total_amount = $quotation->total_amount;
        $this->openModal();
    }

    public function delete(Quotation $quotation)
    {
        $quotation->delete();
        session()->flash('message', 'Penawaran berhasil dihapus.');
    }
}
