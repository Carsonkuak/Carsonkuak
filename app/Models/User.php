<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'otp',
        'is_verified'
    ];

    protected $hidden = [
        'password',
        'otp'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function addresses()
    {
        return $this->hasMany(VegeAddress::class, 'u_id');
    }

}
