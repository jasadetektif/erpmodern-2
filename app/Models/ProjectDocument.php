<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDocument extends Model
{
    use HasFactory;
    protected $fillable = ['project_id', 'uploaded_by_id', 'name', 'path', 'type'];

    public function project() { return $this->belongsTo(Project::class); }
    public function uploadedBy() { return $this->belongsTo(User::class, 'uploaded_by_id'); }
}
