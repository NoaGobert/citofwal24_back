<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\UserAddress;
use App\Models\UserPhone;
use App\Models\Food;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'firstname',
        'lastname',
        'username',
        'status',
        'email',
        'password',
    ];

    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'uuid';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function address()
    {
        return $this->hasMany(UserAddress::class, 'users_uuid', 'uuid');
    }

    public function phone()
    {
        return $this->hasMany(UserPhone::class, 'users_uuid', 'uuid')->oldest();
    }

    public function food()
    {
        return $this->hasMany(Food::class, 'donator_id', 'uuid');
    }
}