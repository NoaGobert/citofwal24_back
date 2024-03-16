<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::where('is_active', 1)->with('addresses')->get();
        return $users;
    }

    public function show($id)
    {
        $user = User::where('is_active', 1)->where('uuid', $id)->with('addresses')->first();
        if (!$user) {
            return response()->json([
                "message" => "User not found"
            ], 404);
        }
        return $user;
    }

    public function store(Request $request)
    {
        // $validator = $request->validate([
        //     "firstname" => "required",
        //     "lastname" => "required",
        //     "phone" => "required",
        //     "email" => "required|email|unique:users",
        //     "password" => "required",
        //     "confirm_password" => "required|same:password",
        // ]);

        // $user = new User;
        // $user->uuid = Str::uuid();
        // $user->firstname = $request->firstname;
        // $user->lastname = $request->lastname;
        // $user->email = $request->email;
        // $user->password = bcrypt($request->password);
        // $user->save();

        // return response()->json([
        //     "message" => "User created successfully",
        //     "user" => $user
        // ], 201);
    }

    public function update(Request $request)
    {
        $validator = $request->validate([
            "firstname" => "required",
            "lastname" => "required",
            "phone" => "required",
            "email" => "required|email|unique:users",
            "password" => "required",
            "confirm_password" => "required|same:password",
        ]);

        $user = User::find($request->id);

        if (!$user) {
            return response()->json([
                "message" => "User not found"
            ], 404);
        }

        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            "message" => "User updated successfully",
            "user" => $user
        ], 200);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->is_active = 0;
        $user->save();

        return response()->json([
            "message" => "User deleted successfully"
        ], 200);
    }
}
