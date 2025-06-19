<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ProjectTeam extends Model
{
    use HasFactory;
    protected $fillable = [
    'project_id', 'employee_id', 'number_of_workers', 'worker_daily_wage', 
    'payment_type', 'lump_sum_value', 'work_progress' // <-- Tambahkan ini
];
    public function project() { return $this->belongsTo(Project::class); }
    public function employee() { return $this->belongsTo(Employee::class); }
}
