<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMaintenance extends Model
{
    use HasFactory;
    protected $fillable = ['asset_id', 'maintenance_date', 'type', 'description', 'cost', 'conducted_by_id'];

    public function asset() { return $this->belongsTo(Asset::class); }
    public function conductedBy() { return $this->belongsTo(User::class, 'conducted_by_id'); }
}
