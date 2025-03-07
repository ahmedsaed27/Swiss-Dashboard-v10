<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = "groups";

    protected $fillable = [
        'code',
        'course_id',
        'instructor_id',
        'status',
        'max_students',
        'current_count',
        'start_date',
        'end_date',
        'postponed_at',
    ];

    public $timestamps = true;

    /**
     * Get the course associated with the group.
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Get the instructor associated with the group.
     */
    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }

    /**
     * Get the students in the group.
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'user_group', 'group_id', 'user_id')
            ->withPivot('joined_at')
            ->withTimestamps();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($group) {
            $group->code = $group->generateCode($group->course_id);
        });
    }

    public function generateCode(int $course_id)
    {
        $course = Course::find($course_id);
        return $course->information()
        ->where('language_id', Language::query()->where('code', 'en')->first()->id)
        ->first()->title . '-' . now()->format('Y-F-d');
    }
}
