<?php

namespace App\Livewire; // atau App\Livewire\Dashboard

use Livewire\Component;
use App\Models\Project;
use App\Models\PurchaseRequest;
use App\Models\Invoice;
use App\Models\ClientPayment;
use App\Models\Payment;
use App\Models\Employee;
use App\Models\Announcement;
use App\Models\ProjectTeam;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Dashboard extends Component // atau class Index extends Component
{
    // --- Properti Filter ---
    public $projects;
    public $selectedProjectId = 'all';

    // --- Properti Personalisasi ---
    public $greeting;
    public $quote;
    public $activityFeed = [];
    public $latestAnnouncement;

    // --- Properti Statistik & Chart ---
    public $activeProjects = 0;
    public $totalWorkers = 0;
    public $pendingPrCount = 0;
    public $overdueInvoicesCount = 0;
    public array $profitabilityChartData = [];

    public function mount()
    {
        $this->projects = Project::orderBy('name')->get();
        $this->setGreeting();
        $this->setRandomQuote();
        $this->loadDashboardData();
    }

    public function updatedSelectedProjectId()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        $this->loadActivityFeed();
        $this->loadActionCenterData();
        $this->loadOperationalStats();
        $this->loadProfitabilityChartData();
        $this->latestAnnouncement = Announcement::where('is_active', true)->latest()->first();
    }
    
    public function loadActivityFeed()
    {
        $query = PurchaseRequest::with('requester');
        if ($this->selectedProjectId !== 'all') {
            $query->where('project_id', $this->selectedProjectId);
        }
        $latestPr = $query->latest()->take(5)->get();
        // ... (logika feed lainnya bisa ditambahkan di sini)
        
        $activities = collect();
        foreach($latestPr as $pr) {
            $activities->push(['type' => 'pr', 'data' => $pr, 'timestamp' => $pr->created_at]);
        }
        $this->activityFeed = $activities->sortByDesc('timestamp')->take(5)->all();
    }

    public function loadActionCenterData()
    {
        $prQuery = PurchaseRequest::where('status', 'Menunggu Persetujuan');
        $invoiceQuery = Invoice::where('status', 'Belum Dibayar')->where('due_date', '<', now());

        if ($this->selectedProjectId !== 'all') {
            $prQuery->where('project_id', $this->selectedProjectId);
            $invoiceQuery->whereHas('purchaseOrder.purchaseRequest', function ($q) {
                $q->where('project_id', $this->selectedProjectId);
            });
        }
        $this->pendingPrCount = $prQuery->count();
        $this->overdueInvoicesCount = $invoiceQuery->count();
    }
    
    public function loadOperationalStats()
    {
        $projectQuery = Project::where('status', 'Berjalan');
        $teamQuery = new ProjectTeam;
        
        if ($this->selectedProjectId !== 'all') {
            $projectQuery->where('id', $this->selectedProjectId);
            $teamQuery = $teamQuery->where('project_id', $this->selectedProjectId);
        }
        $this->activeProjects = $projectQuery->count();
        $this->totalWorkers = $teamQuery->sum('number_of_workers');
    }
    
    public function loadProfitabilityChartData()
    {
        $projectQuery = Project::with(['clientPayments', 'purchaseOrders']);
        if ($this->selectedProjectId !== 'all') {
            $projectQuery->where('id', $this->selectedProjectId);
        } else {
            $projectQuery->where('status', 'Berjalan')->limit(5);
        }
        $projects = $projectQuery->get();
        
        $labels = []; $revenues = []; $expenses = [];
        foreach ($projects as $project) {
            $labels[] = $project->name;
            $revenues[] = $project->clientPayments->sum('amount');
            $expenses[] = $project->purchaseOrders->sum('total_amount');
        }
        $this->profitabilityChartData = ['labels' => $labels, 'revenues' => $revenues, 'expenses' => $expenses];
    }
    
    public function setGreeting()
    {
        $hour = now()->setTimezone('Asia/Jakarta')->format('H');
        if ($hour < 11) { $this->greeting = 'Selamat Pagi'; } 
        elseif ($hour < 15) { $this->greeting = 'Selamat Siang'; } 
        elseif ($hour < 18) { $this->greeting = 'Selamat Sore'; } 
        else { $this->greeting = 'Selamat Malam'; }
    }

    public function setRandomQuote()
    {
        $quote = \App\Models\Quote::where('is_active', true)->inRandomOrder()->first();
        $this->quote = $quote ? $quote->text : 'Selamat bekerja keras hari ini!';
    }

    public function render()
    {
        return view('livewire.dashboard'); // atau 'livewire.dashboard.index'
    }
}
