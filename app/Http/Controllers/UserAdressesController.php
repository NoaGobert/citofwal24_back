<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserAdressesController extends Controller
{
    public function show($id)
    {
        $user = User::where('is_active', 1)->find($id);
        return $user;
    }
}
