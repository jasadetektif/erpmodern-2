<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\Employee;
use App\Models\PurchaseRequest;
use App\Models\PurchaseOrder;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $activeProjects;
    public $pendingPRs;
    public $activeEmployees;
    public $monthlyExpense;

    public array $statusChartData = [];
    public array $expenseChartData = [];

    public function mount()
    {
        $this->activeProjects = Project::where('status', 'Berjalan')->count();
        $this->pendingPRs = PurchaseRequest::where('status', 'Menunggu Persetujuan')->count();
        $this->activeEmployees = Employee::where('status', 'Aktif')->count();

        $this->monthlyExpense = PurchaseOrder::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        $this->statusChartData = $this->getStatusChart();
        $this->expenseChartData = $this->getExpenseChart();
    }

    protected function getStatusChart()
    {
        $data = Project::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        return [
            'labels' => $data->keys()->toArray(),
            'data' => $data->values()->toArray(),
        ];
    }

    protected function getExpenseChart()
    {
        $rows = DB::table('projects')
            ->join('purchase_requests', 'projects.id', '=', 'purchase_requests.project_id')
            ->join('purchase_orders', 'purchase_requests.id', '=', 'purchase_orders.purchase_request_id')
            ->select('projects.name', DB::raw('SUM(purchase_orders.total_amount) as total'))
            ->groupBy('projects.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return [
            'labels' => $rows->pluck('name'),
            'data' => $rows->pluck('total'),
        ];
    }

    public function render()
    {
        return view('dashboard');
    }
}
