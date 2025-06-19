<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = ['employee_id', 'project_id', 'date', 'status', 'notes'];

    public function employee() { return $this->belongsTo(Employee::class); }
    public function project() { return $this->belongsTo(Project::class); }
}