<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class StudentsGroup extends Model
{
    protected $fillable = [
        'name',
    ];

    public function students()
    {
        return $this->hasMany('App\Model\Student', 'group_id','id');
    }

    public function classes()
    {
        return $this->hasMany('App\Model\StudyClass','group_id','id');
    }
}
