<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class StudyWeek extends Model
{
    protected $fillable = [
        'is_even',
        'created_at',
    ];

    protected $dates = ['created_at'];

    public function days()
    {
        return $this->hasMany('App\Model\StudyDay', 'study_week_id','id');
    }
}
