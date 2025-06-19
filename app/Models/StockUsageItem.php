<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockUsageItem extends Model
{
    use HasFactory;
    protected $fillable = ['stock_usage_id', 'inventory_id', 'used_quantity', 'notes'];

    public function stockUsage() { return $this->belongsTo(StockUsage::class); }
    public function inventory() { return $this->belongsTo(Inventory::class); }
}