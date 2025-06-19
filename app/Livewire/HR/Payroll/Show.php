<?php

namespace App\Livewire\HR\Payroll;

use Livewire\Component;
use App\Models\Payroll;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Show extends Component
{
    public Payroll $payroll;

    public function mount(Payroll $payroll)
    {
        // Muat semua relasi yang dibutuhkan
        $this->payroll = $payroll->load('payslips.employee.user');
    }

    public function render()
    {
        return view('livewire.hr.payroll.show');
    }
}
