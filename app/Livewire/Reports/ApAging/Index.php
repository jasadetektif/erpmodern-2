<?php

namespace App\Livewire\Reports\ApAging;

use Livewire\Component;
use App\Models\Invoice;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public $agingData = [];
    public $totals = [];
    public $reportDate;

    public function mount()
    {
        $this->reportDate = now()->format('Y-m-d');
        $this->generateReport();
    }

    public function generateReport()
    {
        $today = Carbon::parse($this->reportDate);
        $invoices = Invoice::where('status', 'Belum Dibayar')->with('supplier')->get();

        $buckets = [
            'current' => [],
            'days_1_30' => [],
            'days_31_60' => [],
            'days_61_90' => [],
            'over_90' => [],
        ];

        foreach ($invoices as $invoice) {
            $dueDate = Carbon::parse($invoice->due_date);
            if ($dueDate->isFuture() || $dueDate->isToday()) {
                $buckets['current'][] = $invoice;
            } else {
                $daysOverdue = $dueDate->diffInDays($today);
                if ($daysOverdue <= 30) {
                    $buckets['days_1_30'][] = $invoice;
                } elseif ($daysOverdue <= 60) {
                    $buckets['days_31_60'][] = $invoice;
                } elseif ($daysOverdue <= 90) {
                    $buckets['days_61_90'][] = $invoice;
                } else {
                    $buckets['over_90'][] = $invoice;
                }
            }
        }

        $this->agingData = $buckets;

        // Hitung total untuk setiap bucket
        foreach ($buckets as $key => $bucketInvoices) {
            $this->totals[$key] = collect($bucketInvoices)->sum('total_amount');
        }
    }
    
    public function render()
    {
        return view('livewire.reports.ap-aging.index');
    }
}
