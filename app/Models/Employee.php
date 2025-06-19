<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id', 'employee_id_number', 'position', 'join_date', 'phone', 'address', 'status', 'basic_salary',
    'allowance_transport', 'allowance_meal', 'deduction_pph21', 'deduction_bpjs_tk', 'deduction_bpjs_kesehatan' // Tambahkan ini
];

    public function attendances()
{
    return $this->hasMany(Attendance::class);
}

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function projectTeams() { return $this->hasMany(\App\Models\ProjectTeam::class); }
    public function getLinkAttribute() { return route('hr.employees.show', $this->id); }
    public function getTitleAttribute() { return $this->user->name ?? $this->employee_id_number; }
    public function getSubtitleAttribute() { return $this->position; }
    
}

