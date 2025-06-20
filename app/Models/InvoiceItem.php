<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;
    protected $fillable = ['invoice_id', 'purchase_order_item_id', 'description', 'quantity', 'price', 'total'];

    public function invoice() { return $this->belongsTo(Invoice::class); }
}