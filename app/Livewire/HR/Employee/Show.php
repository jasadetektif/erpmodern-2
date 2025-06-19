<?php

namespace App\Livewire\HR\Employee;

use Livewire\Component;
use App\Models\Employee;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Show extends Component
{
    public Employee $employee;

    public function mount(Employee $employee)
    {
        // Muat relasi user agar bisa ditampilkan di view
        $this->employee = $employee->load('user');
    }

    public function render()
    {
        return view('livewire.hr.employee.show');
    }
}
