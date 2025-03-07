<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Coupon extends Model
{
    protected $table = "coupons";
    protected $fillable = [
        'name',
        'code',
        'type',
        'value',
        'courses',
        'start_date',
        'end_date'
    ];
    public $timestamps = true;

    public function courses(): Attribute
    {
        return Attribute::make(
            get: fn($value) => is_string($value) ? json_decode($value, true) ?? [] : ($value ?? []),
            set: fn($value) => json_encode($value ?? [])
        );
    }
}
