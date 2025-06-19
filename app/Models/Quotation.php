<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;
    protected $fillable = ['quotation_number', 'client_id', 'created_by_id','quotation_number', 'created_by_id', 'client_name', 'client_contact', 'quotation_date', 'valid_until_date', 'total_amount', 'status', 'notes'];

    public function createdBy() { return $this->belongsTo(User::class, 'created_by_id'); }
    public function items() { return $this->hasMany(QuotationItem::class); }
    public function client() { return $this->belongsTo(Client::class);}
    public function getLinkAttribute() { return route('sales.quotations.show', $this->id); }
    public function getTitleAttribute() { return $this->quotation_number; }
    public function getSubtitleAttribute() { return $this->client_name; }

}


