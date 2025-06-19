<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payslip extends Model
{
    use HasFactory;
    protected $fillable = ['payroll_id', 'employee_id', 'gross_salary', 'total_deductions', 'net_salary'];

    public function payroll() { return $this->belongsTo(Payroll::class); }
    public function employee() { return $this->belongsTo(Employee::class); }
    public function items() { return $this->hasMany(PayslipItem::class); }
}
