<?php

namespace App\Http\Controllers;

use App\Models\UserPhone;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::where('is_active', 1)->with('addresses')->get();
        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::where('is_active', 1)->where('uuid', $id)->with('addresses')->first();
        if (!$user) {
            return response()->json([
                "message" => "User not found"
            ], 404);
        }
        return response()->json($user);
    }


    public function update(Request $request, $uuid)
    {
        try {
            $validator = $request->validate([
                "firstname" => "required",
                "lastname" => "required",
                "phone" => "required",
                "email" => "required|email|unique:users",
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                "message" => $e->errors()
            ], 400);
        }

        $user = User::where('uuid', $uuid)->first();
        if (!$user) {
            return response()->json([
                "message" => "User not found"
            ], 404);
        }
        $this->authorize('verify', $user);


        if ($user->phone != $request->phone) {
            UserPhone::create([
                "users_uuid" => $uuid,
                "phone" => $request->phone
            ]);
        }

        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;

        $user->save();

        return response()->json([
            "message" => "User updated successfully",
            "user" => $user
        ], 200);
    }

    public function destroy($id)
    {
        $user = User::where('uuid', $id)->first();
        if (!$user) {
            return response()->json([
                "message" => "User not found"
            ], 404);
        }
        $this->authorize('verify', $user);
        $user->is_active = 0;
        $user->save();

        return response()->json([
            "message" => "User deleted successfully"
        ], 200);
    }
}