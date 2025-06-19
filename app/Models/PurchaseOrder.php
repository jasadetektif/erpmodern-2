<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;
    protected $fillable = ['po_number', 'purchase_request_id', 'supplier_id', 'order_by_id', 'order_date', 'total_amount', 'status', 'notes'];

    public function purchaseRequest() { return $this->belongsTo(PurchaseRequest::class); }
    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function orderBy() { return $this->belongsTo(User::class, 'order_by_id'); }
    public function items() { return $this->hasMany(PurchaseOrderItem::class); }
    public function invoice() {return $this->hasOne(\App\Models\Invoice::class);}
    public function getLinkAttribute() { return route('procurement.po.show', $this->id); }
    public function getTitleAttribute() { return $this->po_number; }
    public function getSubtitleAttribute() { return 'Pemasok: ' . ($this->supplier->name ?? 'N/A'); }

}

