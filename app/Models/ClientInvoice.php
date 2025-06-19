<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientInvoice extends Model
{
    use HasFactory;
    protected $fillable = ['invoice_number', 'project_id', 'description', 'invoice_date', 'due_date', 'amount', 'status'];

    public function project() { return $this->belongsTo(Project::class); }
    public function payments() { return $this->hasMany(ClientPayment::class);}
}