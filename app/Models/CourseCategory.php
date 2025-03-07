<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseCategory extends Model
{
    protected $table = "course_categories";
    protected $fillable = ['language_id', 'icon', 'color', 'name', 'slug', 'status', 'serial_number', 'is_featured'];

    public function categoryLang()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    //   public function courseInfoList()
    //   {
    //     return $this->hasMany(CourseInformation::class);
    //   }
}
