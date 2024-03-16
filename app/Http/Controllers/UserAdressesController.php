<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class UserAdressesController extends Controller
{
    public function show($id)
    {
        $user = User::where('is_active', 1)->find($id);
        return $user;
    }

    public function store(Request $request, $id)
    {
        $user = User::find($id);
        $user->addresses()->create($request->all());
        return $user->addresses;
    }

    public function destroy($id)
    {
        $adress = UserAddress::find($id);
        $adress->is_active = 0;
        $adress->save();
    }
}
