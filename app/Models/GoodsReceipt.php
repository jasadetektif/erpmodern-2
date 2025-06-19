<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsReceipt extends Model
{
    use HasFactory;
    protected $fillable = ['gr_number', 'purchase_order_id', 'received_by_id', 'receipt_date', 'status', 'notes'];

    public function purchaseOrder() { return $this->belongsTo(PurchaseOrder::class); }
    public function receivedBy() { return $this->belongsTo(User::class, 'received_by_id'); }
    public function items() { return $this->hasMany(GoodsReceiptItem::class); }
}
