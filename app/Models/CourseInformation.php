<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseInformation extends Model
{
    use HasFactory;

    protected $table = 'course_informations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'language_id',
        'course_category_id',
        'course_id',
        'title',
        'slug',
        'features',
        'description',
        'meta_keywords',
        'meta_description',
        'thanks_page_content'
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function courseCategory()
    {
        return $this->belongsTo(CourseCategory::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }


    // public function module()
    // {
    //     return $this->hasMany(Module::class);
    // }
}
