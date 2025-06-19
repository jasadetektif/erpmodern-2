<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_code', 'name', 'description', 'purchase_date', 'purchase_price', 'status', 'current_project_id'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'current_project_id');
    }
    public function maintenances()
{
    return $this->hasMany(AssetMaintenance::class)->latest();
}
public function getLinkAttribute() { return route('assets.show', $this->id); }
public function getTitleAttribute() { return $this->name; }
public function getSubtitleAttribute() { return $this->asset_code; }
}
