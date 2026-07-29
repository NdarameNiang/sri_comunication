<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Structure extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'acronym'];

    public function porteurs()
    {
        return $this->hasMany(User::class)->where('role', 'porteur_projet');
    }

    public function pointFocaux()
    {
        return $this->belongsToMany(User::class, 'user_structures')->withTimestamps();
    }

    public function projectAssignments()
    {
        return $this->hasMany(ProjectAssignment::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function getProjectCountAttribute(): int
    {
        return $this->projectAssignments()->count();
    }

    public static function maxProjectsPerStructure(): int
    {
        return EventConfig::active()?->max_projects_per_structure ?? 5;
    }

    public function getRemainingSlots(): int
    {
        return max(0, static::maxProjectsPerStructure() - $this->projectAssignments()->count());
    }

    public function canAddProjects(int $count = 1): bool
    {
        return ($this->projectAssignments()->count() + $count) <= static::maxProjectsPerStructure();
    }
}
