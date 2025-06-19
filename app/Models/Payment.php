<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = ['payment_number', 'invoice_id', 'payment_date', 'amount', 'payment_method', 'processed_by_id', 'notes'];

    public function invoice() { return $this->belongsTo(Invoice::class); }
    public function processedBy() { return $this->belongsTo(User::class, 'processed_by_id'); }
}
