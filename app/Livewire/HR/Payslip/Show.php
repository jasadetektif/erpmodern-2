<?php

namespace App\Livewire\HR\Payslip;

use Livewire\Component;
use App\Models\Payslip;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Show extends Component
{
    public Payslip $payslip;

    public function mount(Payslip $payslip)
    {
        // Muat semua relasi yang dibutuhkan
        $this->payslip = $payslip->load('employee.user', 'payroll', 'items');
    }

    public function render()
    {
        return view('livewire.hr.payslip.show');
    }
}
