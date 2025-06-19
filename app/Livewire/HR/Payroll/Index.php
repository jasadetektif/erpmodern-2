<?php

namespace App\Livewire\HR\Payroll;

use Livewire\Component;
use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public $payrolls;

    // Properti untuk Form
    public $payrollId;
    public $payroll_period, $start_date, $end_date;
    public $isModalOpen = false;

    public function render()
    {
        $this->payrolls = Payroll::latest()->get();
        return view('livewire.hr.payroll.index');
    }

    public function create()
    {
        $this->resetForm();
        $this->openModal();
    }

    public function openModal() { $this->isModalOpen = true; }
    public function closeModal() { $this->isModalOpen = false; }

    private function resetForm()
    {
        $this->payrollId = null;
        $this->payroll_period = 'Periode ' . date('F Y');
        $this->start_date = date('Y-m-01');
        $this->end_date = date('Y-m-t');
    }

    public function store()
    {
        $this->validate([
            'payroll_period' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Payroll::create([
            'payroll_period' => $this->payroll_period,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

        session()->flash('message', 'Periode penggajian berhasil dibuat.');
        $this->closeModal();
    }

    public function processPayroll(Payroll $payroll)
    {
        if ($payroll->status !== 'Draft') {
            session()->flash('error', 'Penggajian untuk periode ini sudah diproses.');
            return;
        }

        DB::transaction(function () use ($payroll) {
            $employees = Employee::where('status', 'Aktif')->get();
            // Asumsi hari kerja dalam sebulan, bisa disesuaikan atau diambil dari pengaturan
            $workingDaysInMonth = 25; 

            foreach ($employees as $employee) {
                // Hitung jumlah hari hadir karyawan dalam periode penggajian
                $presentDays = Attendance::where('employee_id', $employee->id)
                    ->whereBetween('date', [$payroll->start_date, $payroll->end_date])
                    ->where('status', 'Hadir')
                    ->count();

                // Buat Payslip utama
                $payslip = $payroll->payslips()->create([
                    'employee_id' => $employee->id,
                ]);

                // --- LOGIKA PERHITUNGAN GAJI BARU ---

                // Tambahkan komponen Pendapatan (Earnings) secara proporsional
                $proratedBasicSalary = ($presentDays / $workingDaysInMonth) * $employee->basic_salary;
                $proratedTransport = ($presentDays / $workingDaysInMonth) * $employee->allowance_transport;
                $proratedMeal = ($presentDays / $workingDaysInMonth) * $employee->allowance_meal;

                $payslip->items()->create(['description' => 'Gaji Pokok (Berdasarkan Kehadiran)', 'type' => 'earning', 'amount' => $proratedBasicSalary]);
                if ($proratedTransport > 0) {
                    $payslip->items()->create(['description' => 'Tunjangan Transport', 'type' => 'earning', 'amount' => $proratedTransport]);
                }
                if ($proratedMeal > 0) {
                    $payslip->items()->create(['description' => 'Uang Makan', 'type' => 'earning', 'amount' => $proratedMeal]);
                }

                // Tambahkan komponen Potongan (Deductions) - untuk saat ini kita anggap tetap
                if ($employee->deduction_pph21 > 0) {
                    $payslip->items()->create(['description' => 'Potongan PPh 21', 'type' => 'deduction', 'amount' => $employee->deduction_pph21]);
                }
                if ($employee->deduction_bpjs_tk > 0) {
                    $payslip->items()->create(['description' => 'Potongan BPJS TK', 'type' => 'deduction', 'amount' => $employee->deduction_bpjs_tk]);
                }
                if ($employee->deduction_bpjs_kesehatan > 0) {
                    $payslip->items()->create(['description' => 'Potongan BPJS Kesehatan', 'type' => 'deduction', 'amount' => $employee->deduction_bpjs_kesehatan]);
                }
                
                // Tambahkan info kehadiran untuk transparansi
                $payslip->items()->create(['description' => "Total Kehadiran", 'type' => 'earning', 'amount' => 0]); // Ini hanya untuk label
                $payslip->items()->create(['description' => $presentDays . ' / ' . $workingDaysInMonth . ' hari', 'type' => 'earning', 'amount' => 0]);


                // Hitung ulang total pendapatan dan potongan
                $totalEarnings = $payslip->items()->where('type', 'earning')->sum('amount');
                $totalDeductions = $payslip->items()->where('type', 'deduction')->sum('amount');
                
                // Update total di payslip
                $payslip->update([
                    'gross_salary' => $totalEarnings,
                    'total_deductions' => $totalDeductions,
                    'net_salary' => $totalEarnings - $totalDeductions,
                ]);
            }

            // Update status periode payroll
            $payroll->update(['status' => 'Selesai']);
        });

        session()->flash('message', 'Penggajian untuk periode ' . $payroll->payroll_period . ' berhasil diproses.');
    }

    public function delete(Payroll $payroll)
    {
        $payroll->delete();
        session()->flash('message', 'Periode penggajian berhasil dihapus.');
    }
}
