<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = "courses";

    protected $fillable = [
        'thumbnail_image',
        'video_link',
        'cover_image',
        'pricing_type',
        'previous_price',
        'current_price',
        'status',
        'is_featured',
        'average_rating',
        'duration',
        'certificate_status',
        'video_watching',
        'quiz_completion',
        'certificate_title',
        'certificate_text',
        'min_quiz_score'
    ];

    public $timestamps = true;

    // public function setDescriptionAttribute($value){
    //     $this->attributes['description'] = json_encode($value);
    // }

    // public function getDescriptionAttribute($value){
    //     return json_decode($value);
    // }

    public function information()
    {
        return $this->hasMany(CourseInformation::class, 'course_id');
    }
    

    public function instructors()
    {
        return $this->belongsToMany(Instructor::class, 'course_instructor', 'course_id', 'instructor_id')
            ->withTimestamps();
    }


    // public function faq()
    // {
    //     return $this->hasMany(CourseFaq::class);
    // }

    // public function enrolment()
    // {
    //     return $this->hasMany(CourseEnrolment::class, 'course_id', 'id');
    // }

    // public function review()
    // {
    //     return $this->hasMany(CourseReview::class, 'course_id', 'id');
    // }

    // public function quizScore()
    // {
    //     return $this->hasMany(QuizScore::class, 'course_id', 'id');
    // }
}
