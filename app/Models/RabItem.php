<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RabItem extends Model
{
    use HasFactory;
    protected $fillable = ['rab_id', 'category', 'description', 'quantity', 'unit', 'unit_price', 'total_price'];
    public function rab() { return $this->belongsTo(Rab::class); }
}
