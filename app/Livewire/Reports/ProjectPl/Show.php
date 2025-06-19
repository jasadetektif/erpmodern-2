<?php

namespace App\Livewire\Reports\ProjectPl;

use Livewire\Component;
use App\Models\Project;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Show extends Component
{
    public Project $project;

    // Deklarasikan properti publik di sini
    public $totalExpenses;
    public $actualRevenue;
    public $profitOrLoss;
    public $contractValue;

    public function mount(Project $project)
    {
        $this->project = $project->load('purchaseOrders.supplier', 'teams.employee.user', 'clientInvoices', 'clientPayments');

        $materialExpenses = $this->project->purchaseOrders->sum('total_amount');
        $laborExpenses = $this->project->total_labor_cost;
        $this->totalExpenses = $materialExpenses + $laborExpenses;

        // Kalkulasi baru
        $this->actualRevenue = $this->project->clientPayments->sum('amount');
        $this->profitOrLoss = $this->actualRevenue - $this->totalExpenses;
        $this->contractValue = $this->project->budget;
    }

    public function render()
    {
        return view('livewire.reports.project-pl.show');
    }
}
