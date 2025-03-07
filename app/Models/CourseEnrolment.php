<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseEnrolment extends Model
{
    protected $table = "course_enrolments";
    
    protected $fillable = [
        'user_id',
        'order_id',
        'billing_first_name',
        'billing_last_name',
        'billing_email',
        'billing_contact_number',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_country',
        'course_id',
        'course_price',
        'discount',
        'grand_total',
        'currency_text',
        'currency_text_position',
        'currency_symbol',
        'currency_symbol_position',
        'payment_method',
        'gateway_type',
        'payment_status',
        'attachment',
        'invoice'
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
