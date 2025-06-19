<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'pr_number', 'project_id', 'requester_id', 'request_date', 'status', 'notes'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function items()
    {
        return $this->hasMany(PurchaseRequestItem::class);
    }
public function getLinkAttribute() { return route('procurement.pr.show', $this->id); }
public function getTitleAttribute() { return $this->pr_number; }
public function getSubtitleAttribute() { return 'Proyek: ' . ($this->project->name ?? 'N/A'); }

}