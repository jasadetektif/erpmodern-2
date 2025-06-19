<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsReceiptItem extends Model
{
    use HasFactory;
    protected $fillable = ['goods_receipt_id', 'purchase_order_item_id', 'received_quantity', 'notes'];

    public function goodsReceipt() { return $this->belongsTo(GoodsReceipt::class); }
    public function purchaseOrderItem() { return $this->belongsTo(PurchaseOrderItem::class); }
}
