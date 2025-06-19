<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    use HasFactory;
    protected $fillable = ['purchase_order_id', 'purchase_request_item_id', 'item_name', 'quantity', 'unit', 'price', 'total_price'];

    public function purchaseOrder() { return $this->belongsTo(PurchaseOrder::class); }
}
