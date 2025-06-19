<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_name', 'contact_person', 'email', 'phone', 'address'
    ];


public function quotations()
{
    return $this->hasMany(Quotation::class);
}
public function getTitleAttribute() { return $this->client_name; }
public function getSubtitleAttribute() { return $this->contact_person; }
public function getLinkAttribute() { return '#'; } // Belum ada halaman detail klien



}