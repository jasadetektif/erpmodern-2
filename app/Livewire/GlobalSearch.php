<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\Quotation;
use App\Models\Client;
use App\Models\PurchaseRequest;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Employee;
use App\Models\Asset;
use Livewire\Attributes\On;

class GlobalSearch extends Component
{
    public $search = '';
    public $results = [];
    public $show = false;

    #[On('open-search')]
    public function open()
    {
        $this->show = true;
    }

    public function updatedSearch()
    {
        if (strlen($this->search) < 3) {
            $this->results = [];
            return;
        }

        $this->results = [];
        $searchTerm = '%' . $this->search . '%';

        // Proyek
        $this->results['projects'] = Project::where('name', 'like', $searchTerm)
                                            ->orWhere('client', 'like', $searchTerm)
                                            ->limit(3)->get();
        
        // Penawaran
        $this->results['quotations'] = Quotation::where('quotation_number', 'like', $searchTerm)
                                                ->orWhere('client_name', 'like', $searchTerm)
                                                ->limit(3)->get();
        
        // Klien
        $this->results['clients'] = Client::where('client_name', 'like', $searchTerm)
                                          ->orWhere('contact_person', 'like', $searchTerm)
                                          ->limit(3)->get();

        // Purchase Request (Diperbaiki: Mencari berdasarkan nama proyek juga)
        $this->results['purchase_requests'] = PurchaseRequest::with('project')
                                          ->where('pr_number', 'like', $searchTerm)
                                          ->orWhereHas('project', function ($query) use ($searchTerm) {
                                              $query->where('name', 'like', $searchTerm);
                                          })
                                          ->limit(3)->get();
                                          
        // Purchase Order (Diperbaiki: Mencari berdasarkan nama proyek dan supplier)
        $this->results['purchase_orders'] = PurchaseOrder::with('purchaseRequest.project', 'supplier')
                                          ->where('po_number', 'like', $searchTerm)
                                          ->orWhereHas('purchaseRequest.project', function ($query) use ($searchTerm) {
                                              $query->where('name', 'like', $searchTerm);
                                          })
                                          ->orWhereHas('supplier', function ($query) use ($searchTerm) {
                                              $query->where('name', 'like', $searchTerm);
                                          })
                                          ->limit(3)->get();

        // Karyawan (Mencari di tabel user)
        $this->results['employees'] = Employee::whereHas('user', function ($query) use ($searchTerm) {
                                                $query->where('name', 'like', $searchTerm);
                                           })
                                           ->orWhere('position', 'like', $searchTerm)
                                           ->limit(3)->get();

        // Aset
        $this->results['assets'] = Asset::where('name', 'like', $searchTerm)
                                       ->orWhere('asset_code', 'like', $searchTerm)
                                       ->limit(3)->get();
    }

    public function resetSearch()
    {
        $this->search = '';
        $this->results = [];
        $this->show = false;
    }

    public function render()
    {
        return view('livewire.global-search');
    }
}

