<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectScoreDetail extends Model
{
    protected $fillable = ['project_score_id', 'evaluation_criterion_id', 'points'];

    public function projectScore()
    {
        return $this->belongsTo(ProjectScore::class);
    }

    public function criterion()
    {
        return $this->belongsTo(EvaluationCriterion::class, 'evaluation_criterion_id');
    }
}
