<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserGroup extends Pivot
{
    protected $table = "user_group";

    protected $fillable = [
        'user_id',
        'group_id',
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
