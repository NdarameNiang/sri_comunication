<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectScore extends Model
{
    protected $fillable = ['project_id', 'evaluator_id', 'status', 'submitted_at'];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
        ];
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function details()
    {
        return $this->hasMany(ProjectScoreDetail::class);
    }

    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }

    public function totalPoints(): float
    {
        return (float) $this->details->sum('points');
    }
}
