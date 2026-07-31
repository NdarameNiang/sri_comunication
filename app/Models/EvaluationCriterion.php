<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationCriterion extends Model
{
    protected $table = 'evaluation_criteria';

    protected $fillable = ['rubric_id', 'label', 'max_points', 'sort_order'];

    public function rubric()
    {
        return $this->belongsTo(EvaluationRubric::class, 'rubric_id');
    }
}
