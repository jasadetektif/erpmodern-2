<?php

namespace App\Livewire\Procurement\PurchaseRequest;

use Livewire\Component;
use App\Models\Project;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use App\Models\User;
use App\Notifications\NewPrNotification;
use Illuminate\Support\Facades\Notification;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public $purchaseRequests;
    public $projects;

    // Properti untuk Form
    public $prId;
    public $project_id;
    public $request_date;
    public $notes;
    public $status;
    public array $items = [];

    public $isModalOpen = false;

    public function mount()
    {
        $this->projects = Project::all();
        $this->purchaseRequests = PurchaseRequest::with('project', 'requester')->latest()->get();
    }

    public function render()
    {
        $this->purchaseRequests = PurchaseRequest::with('project', 'requester')->latest()->get();
        return view('livewire.procurement.purchase-request.index');
    }

    public function create()
    {
        $this->resetForm();
        $this->addItem(); // Langsung tambahkan satu baris item kosong
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetForm()
    {
        $this->prId = null;
        $this->project_id = '';
        $this->request_date = date('Y-m-d');
        $this->notes = '';
        $this->status = 'Draft';
        $this->items = [];
    }

    // Fungsi untuk menambah baris item di form
    public function addItem()
    {
        $this->items[] = ['item_name' => '', 'quantity' => 1, 'unit' => '', 'description' => ''];
    }

    // Fungsi untuk menghapus baris item di form
    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items); // Re-index array
    }

    public function store()
{
    $this->validate([
        'project_id' => 'required|exists:projects,id',
        'request_date' => 'required|date',
        'items' => 'required|array|min:1',
        'items.*.item_name' => 'required|string|max:255',
        'items.*.quantity' => 'required|numeric|min:0.1',
        'items.*.unit' => 'required|string|max:50',
    ]);

    DB::transaction(function () {
        $pr = PurchaseRequest::updateOrCreate(['id' => $this->prId], [
            'pr_number' => $this->prId ? PurchaseRequest::find($this->prId)->pr_number : 'PR-' . time(),
            'project_id' => $this->project_id,
            'requester_id' => Auth::id(),
            'request_date' => $this->request_date,
            'status' => $this->status,
            'notes' => $this->notes,
        ]);

        $pr->items()->delete();

        foreach ($this->items as $item) {
            $pr->items()->create($item);
        }

        // --- LOGIKA NOTIFIKASI BARU DIMULAI DI SINI ---
        if (!$this->prId) { // Hanya kirim notifikasi untuk PR yang baru dibuat
            // Cari semua user dengan peran 'Direktur' atau 'Manajer Proyek'
            $usersToNotify = User::role(['Direktur', 'Manajer Proyek'])->get();
            Notification::send($usersToNotify, new NewPrNotification($pr));
        }
        // --- LOGIKA NOTIFIKASI BARU SELESAI ---
    });

    session()->flash('message', $this->prId ? 'Purchase Request berhasil diperbarui.' : 'Purchase Request berhasil dibuat.');
    $this->closeModal();
}

    public function edit($id)
    {
        $pr = PurchaseRequest::with('items')->findOrFail($id);
        $this->prId = $id;
        $this->project_id = $pr->project_id;
        $this->request_date = $pr->request_date;
        $this->notes = $pr->notes;
        $this->status = $pr->status;
        $this->items = $pr->items->toArray();
        $this->openModal();
    }

    public function delete($id)
    {
        PurchaseRequest::find($id)->delete();
        session()->flash('message', 'Purchase Request berhasil dihapus.');
    }
}
