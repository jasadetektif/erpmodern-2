<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ProjectExpense extends Model
{
    use HasFactory;
    protected $fillable = ['project_id', 'expense_date', 'description', 'amount', 'user_id'];
    public function project() { return $this->belongsTo(Project::class); }
    public function user() { return $this->belongsTo(User::class); }
}