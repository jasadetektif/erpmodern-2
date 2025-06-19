<?php

namespace App\Livewire\HR\Employee;

use Livewire\Component;
use App\Models\Employee;
use App\Models\User;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public $employees;
    public $users; // Untuk dropdown akun pengguna

    // Properti untuk Form
    public $employeeId;
    public $user_id, $employee_id_number, $position, $join_date, $phone, $address, $status;
    
    // Properti Komponen Gaji Baru
    public $basic_salary, $allowance_transport, $allowance_meal, $deduction_pph21, $deduction_bpjs_tk, $deduction_bpjs_kesehatan;

    public $isModalOpen = false;

    public function mount()
    {
        $this->users = User::whereDoesntHave('employee')->get();
    }

    public function render()
    {
        $this->employees = Employee::with('user')->get();
        return view('livewire.hr.employee.index');
    }

    public function create()
    {
        $this->resetForm();
        $this->users = User::whereDoesntHave('employee')->get();
        $this->openModal();
    }

    public function openModal() { $this->isModalOpen = true; }
    public function closeModal() { $this->isModalOpen = false; }

    private function resetForm()
    {
        $this->employeeId = null;
        $this->user_id = '';
        $this->employee_id_number = '';
        $this->position = '';
        $this->join_date = date('Y-m-d');
        $this->phone = '';
        $this->address = '';
        $this->status = 'Aktif';
        $this->basic_salary = 0;
        $this->allowance_transport = 0;
        $this->allowance_meal = 0;
        $this->deduction_pph21 = 0;
        $this->deduction_bpjs_tk = 0;
        $this->deduction_bpjs_kesehatan = 0;
    }

    public function store()
    {
        $this->validate([
            'employee_id_number' => 'required|string|max:255|unique:employees,employee_id_number,' . $this->employeeId,
            'position' => 'required|string|max:255',
            'join_date' => 'required|date',
            'user_id' => 'nullable|exists:users,id',
            'basic_salary' => 'required|numeric|min:0',
            'allowance_transport' => 'required|numeric|min:0',
            'allowance_meal' => 'required|numeric|min:0',
            'deduction_pph21' => 'required|numeric|min:0',
            'deduction_bpjs_tk' => 'required|numeric|min:0',
            'deduction_bpjs_kesehatan' => 'required|numeric|min:0',
        ]);

        // Perbaikan: Pastikan user_id adalah null jika kosong
        $userId = $this->user_id === '' ? null : $this->user_id;

        Employee::updateOrCreate(['id' => $this->employeeId], [
            'user_id' => $userId, // Gunakan variabel yang sudah diperbaiki
            'employee_id_number' => $this->employee_id_number,
            'position' => $this->position,
            'join_date' => $this->join_date,
            'phone' => $this->phone,
            'address' => $this->address,
            'status' => $this->status,
            'basic_salary' => $this->basic_salary,
            'allowance_transport' => $this->allowance_transport,
            'allowance_meal' => $this->allowance_meal,
            'deduction_pph21' => $this->deduction_pph21,
            'deduction_bpjs_tk' => $this->deduction_bpjs_tk,
            'deduction_bpjs_kesehatan' => $this->deduction_bpjs_kesehatan,
        ]);

        session()->flash('message', $this->employeeId ? 'Data karyawan berhasil diperbarui.' : 'Karyawan baru berhasil ditambahkan.');

        $this->closeModal();
    }

    public function edit(Employee $employee)
    {
        $this->employeeId = $employee->id;
        $this->user_id = $employee->user_id;
        $this->employee_id_number = $employee->employee_id_number;
        $this->position = $employee->position;
        $this->join_date = $employee->join_date;
        $this->phone = $employee->phone;
        $this->address = $employee->address;
        $this->status = $employee->status;
        $this->basic_salary = $employee->basic_salary;
        $this->allowance_transport = $employee->allowance_transport;
        $this->allowance_meal = $employee->allowance_meal;
        $this->deduction_pph21 = $employee->deduction_pph21;
        $this->deduction_bpjs_tk = $employee->deduction_bpjs_tk;
        $this->deduction_bpjs_kesehatan = $employee->deduction_bpjs_kesehatan;

        $this->users = User::whereDoesntHave('employee')->orWhere('id', $this->user_id)->get();
        $this->openModal();
    }

    public function delete(Employee $employee)
    {
        $employee->delete();
        session()->flash('message', 'Data karyawan berhasil dihapus.');
    }
}