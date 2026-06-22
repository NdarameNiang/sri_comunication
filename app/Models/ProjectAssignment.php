<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectAssignment extends Model
{
    protected $fillable = ['porteur_id', 'structure_id', 'title', 'status'];

    public function porteur()
    {
        return $this->belongsTo(User::class, 'porteur_id');
    }

    public function structure()
    {
        return $this->belongsTo(Structure::class);
    }

    public function project()
    {
        return $this->hasOne(Project::class, 'assignment_id');
    }

    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }
}
