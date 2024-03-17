<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\UserPhone;
use App\Models\UserAddress;
use GuzzleHttp\Client;

class AuthController extends Controller
{
    public function register(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'username' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'phone' => 'required',
            'street' => 'required',
            'number' => 'required',
            'zip' => 'required',
            'city' => 'required',
            // 'country' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'region' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $userAddress = new UserAddress();
        $response = $userAddress->getLatAndLon($request->street, $request->number, $request->zip, $request->city, $request->country);


        if (empty ($json)) {
            return response()->json([
                'message' => 'Address not found',
            ], 400);
        }

        $latitude = $response[0]['lat'];
        $longitude = $response[0]['lon'];



        $uuid = (string) Str::uuid();

        try {
            DB::beginTransaction();

            User::insertGetId([
                'uuid' => $uuid,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            UserPhone::create([
                'users_uuid' => $uuid,
                'phone' => $request->phone,
            ]);

            UserAddress::create([
                'users_uuid' => $uuid,
                'street' => $request->street,
                'number' => $request->number,
                'zip' => $request->zip,
                'city' => $request->city,
                'country' => "Belgium", // $request->country
                'latitude' => $latitude,
                'longitude' => $longitude,
                'region' => $request->region,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => 'Error while creating user' . $e->getMessage(),
            ], 500);
        }


        return response()->json([
            'message' => 'User Created',
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }


        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            "message" => "logged out"
        ]);
    }

    public function validateToken()
    {
        return response()->json([
            "message" => auth('sanctum')->check()
        ]);
    }

    public function authenticatedUser()
    {
        return response()->json([
            "user" => auth()->user()
        ]);
    }
}