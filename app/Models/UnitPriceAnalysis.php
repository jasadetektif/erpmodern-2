<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitPriceAnalysis extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'unit',
        'total_cost',
    ];

    /**
     * Get all of the analysis's items (materials and labors).
     */
    public function items()
    {
        return $this->hasMany(AnalysisItem::class);
    }
}
