<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jeffgreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable implements FilamentUser
{
  use HasFactory, TwoFactorAuthenticatable, HasRoles, Notifiable;

  protected $table = "admins";
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    // 'role_id',
    'first_name',
    'last_name',
    'image',
    'username',
    'email',
    'password',
    'status'
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = ['password'];

  protected $appends = ['name'];

  public function getNameAttribute(): string
  {
    return $this->first_name . ' ' . $this->last_name;
  }

  public function canAccessPanel(Panel $panel): bool
  {
    return true;
  }

  public function getRoleNamesAttribute(): string
  {
    return $this->roles->pluck('name')->join(',');
  }
}
