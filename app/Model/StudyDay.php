<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class StudyDay extends Model
{
    protected $fillable = [
        'created_at',
        'study_week_id',
    ];

    protected $dates = ['created_at'];

    public function week()
    {
        return $this->belongsTo('App\Model\StudyWeek');
    }

    public function classes()
    {
        return $this->hasMany('App\Model\StudyClass','study_day_id');
    }
}
