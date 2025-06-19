<?php

namespace App\Livewire\Reports\ProjectPl;

use Livewire\Component;
use App\Models\Project;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public $projects;

   public function mount()
{
    $this->projects = Project::with('purchaseOrders', 'teams.employee', 'clientPayments')->get()->map(function ($project) {
        $materialExpenses = $project->purchaseOrders->sum('total_amount');
        $laborExpenses = $project->total_labor_cost;
        $project->total_expenses = $materialExpenses + $laborExpenses;

        // Kalkulasi baru: Pendapatan Riil
        $project->actual_revenue = $project->clientPayments->sum('amount');

        // Kalkulasi baru: Laba/Rugi berdasarkan Pendapatan Riil
        $project->profit_loss = $project->actual_revenue - $project->total_expenses;

        // Ganti nama 'budget' menjadi 'contract_value' untuk kejelasan
        $project->contract_value = $project->budget;

        return $project;
    });
}


    public function render()
    {
        return view('livewire.reports.project-pl.index');
    }
}

