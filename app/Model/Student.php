<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'group_id',
        'student_name',
        'record_book',
    ];

    public function group()
    {
        return $this->belongsTo('App\Model\StudentsGroup');
    }
}
