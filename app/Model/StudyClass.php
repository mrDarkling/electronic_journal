<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class StudyClass extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'study_day_id',
        'group_id',
        'start_time',
        'end_time',
        'study_subject_id',
    ];

    public function day()
    {
        return $this->belongsTo('App\Model\StudyDay');
    }

    public function group()
    {
        return $this->belongsTo('App\Model\StudentsGroup');
    }

    public function subject()
    {
        return $this->belongsTo('App\Model\StudySubject', 'study_subject_id', 'id');
    }
}
