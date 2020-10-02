<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class StudySubject extends Model
{
    protected $fillable = [
        'name',
    ];

    public function study_class()
    {
        return $this->hasMany('App\Model\StudyClass','study_subject_id','id');
    }
}
