<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class UserAdressesController extends Controller
{
    public function show()
    {
        $id = auth()->user()->uuid;
        $user = User::where('is_active', 1)->where('uuid', $id)->with('addresses')->first();
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
