<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPhone extends Model
{
    use HasFactory;

    protected $table = 'users_phones';

    protected $fillable = [
        'users_uuid',
        'phone',
    ];
}
