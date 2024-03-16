<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class food extends Model
{
    use HasFactory;

    protected $table = 'foods';

    protected $fillable = [
        'uuid',
        'name',
        'description',
        'diet',
        'food_category_id',
        'donator_id',
        'receiver_id',
        'is_active',
        'expires_at',
        'group_uuid',
    ];
}
