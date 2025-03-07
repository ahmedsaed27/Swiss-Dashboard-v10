<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'language_id',
    'image',
    'name',
    'occupation',
    'description',
    'is_featured'
  ];

  public function instructorLang()
  {
    return $this->belongsTo(Language::class, 'language_id');
  }


  public function courses()
  {
      return $this->belongsToMany(Course::class, 'course_instructor', 'instructor_id', 'course_id')
                  ->withTimestamps();
  }

  //   public function socialPlatform()
  //   {
  //     return $this->hasMany(SocialLink::class);
  //   }

  //   public function courseList()
  //   {
  //     return $this->hasMany(CourseInformation::class);
  //   }

  //   public function socials()
  //   {
  //     return $this->hasMany(SocialLink::class);
  //   }
}
