<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    use HasFactory;
    protected $fillable = ['quotation_id', 'description', 'quantity', 'unit', 'price', 'total'];

    public function quotation() { return $this->belongsTo(Quotation::class); }
}
