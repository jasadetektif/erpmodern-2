<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'item_name',
        'unit',
        'stock_quantity',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}

