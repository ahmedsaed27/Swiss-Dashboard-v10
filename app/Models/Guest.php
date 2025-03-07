<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\WebPush\HasPushSubscriptions;

class Guest extends Model
{
    use HasFactory, Notifiable, HasPushSubscriptions;

    protected $table = "guests";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['endpoint'];

    public $timestamps = true;
}
