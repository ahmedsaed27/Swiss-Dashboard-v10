<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseInstructor extends Pivot
{
    protected $table = 'course_instructor';

    protected $fillable = [
        'course_id',
        'instructor_id',
    ];

    public $timestamps = true;

    public function instructor()
    {
        return $this->belongsTo(Instructor::class , 'instructor_id');
    }

    public function course(){
        return $this->belongsTo(Course::class ,'course_id');
    }
}
