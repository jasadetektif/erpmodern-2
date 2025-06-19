<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientPayment extends Model
{
    use HasFactory;
    protected $fillable = ['payment_number', 'client_invoice_id', 'payment_date', 'amount', 'payment_method', 'received_by_id', 'notes'];

    public function clientInvoice() { return $this->belongsTo(ClientInvoice::class); }
    public function receivedBy() { return $this->belongsTo(User::class, 'received_by_id'); }
}
