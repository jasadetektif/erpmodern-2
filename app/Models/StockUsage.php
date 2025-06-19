<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockUsage extends Model
{
    use HasFactory;
    protected $fillable = ['usage_number', 'project_id', 'used_by_id', 'usage_date', 'notes'];

    public function project() { return $this->belongsTo(Project::class); }
    public function usedBy() { return $this->belongsTo(User::class, 'used_by_id'); }
    public function items() { return $this->hasMany(StockUsageItem::class); }
}