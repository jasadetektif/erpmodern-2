<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;
    protected $fillable = ['payroll_period', 'start_date', 'end_date', 'status'];

    public function payslips() { return $this->hasMany(Payslip::class); }
}
