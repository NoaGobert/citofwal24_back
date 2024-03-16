<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodStatus extends Model
{
    use HasFactory;


    protected $fillable = [
        'status',
    ];


    public function food()
    {
        return $this->hasMany(Food::class, 'uuid', 'foods_uuid');
    }
}