<?php

namespace App\Http\Controllers;

use App\Models\food;
use App\Models\FoodCategory;
use App\Models\FoodStatus;
use Database\Factories\UserFactory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $foods = Food::where('is_active', true)->where('donator_id', '!=', auth()->user()->uuid)->get();

        return response()->json($foods);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        try {
            $validated = Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'required',
                'diet' => 'required',
                'food_category_id' => 'required|exists:foods_categories,id',
                'donator_id' => 'required|exists:users,uuid',
                'receiver_id' => 'nullable|exists:users,uuid',
            ])->validate();
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 400);
        }


        // Check if food category exists and is active
        $category = FoodCategory::find($validated['food_category_id']);
        if (!$category || !$category->is_active) {
            return response()->json([
                'message' => 'Food category not found'
            ], 400);
        }

        if (isset($validated['receiver_id']) && $validated['receiver_id'] == $validated['donator_id']) {
            return response()->json([
                'message' => 'Donator and receiver cannot be the same'
            ], 400);
        }

        if (User::where('uuid', '=', Auth()->user()->uuid)->groups->isEmpty()) {
            return response()->json([
                'message' => 'You must be part of a group to donate food'
            ], 400);
        }

        $food = Food::create(
            [
                "uuid" => Str::uuid(),
                "name" => $validated['name'],
                "description" => $validated['description'],
                "diet" => $validated['diet'],
                "food_category_id" => $validated['food_category_id'],
                "donator_id" => $validated['donator_id'],
                "receiver_id" => $validated['receiver_id'] ?? null,
                "lat" => $request->lat ?? null,
                "lon" => $request->lon ?? null,
                "location_description" => $request->location_description ?? null,
                "confirmation_code" => random_int(100000000, 999999999),
                "expires_at" => now()->addDays(1),
                "group_uuid" => User::with('groups')->where('uuid', '=', $validated['donator_id'])->first()->groups->pluck('group_uuid')->first()
            ]
        );

        FoodStatus::create([
            "status" => 'available',
            "foods_uuid" => $food->uuid
        ]);


        if (!$request->hasFile('file')) {
            return response()->json([
                'message' => 'No file found in request',
                'food' => $food
            ], 400);
        }

        if ($request->hasFile('files')) {
            $files = $request->file('files');

            if (count($files) > 2) {
                return response()->json([
                    'message' => 'You can only upload a maximum of 2 files',
                ], 400);
            }

            foreach ($files as $key => $file) {
                $file->store('public/foods/' . $food->uuid . '_' . $key . '_' . now()->timestamp);
            }
        } else {
            $file = $request->file('file');
            $file->store('public/foods/' . $food->uuid . '_1_' . now()->timestamp);
        }
        return response()->json([
            'message' => 'Food created successfully',
            'food' => $food
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $food = Food::with('status', 'category')->where('uuid', '=', $id)->where('donator_id', '!=', auth()->user()->uuid)->first();

        return response()->json([
            'food' => $food
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required',
                'description' => 'required',
                'diet' => 'required',
                'food_category_id' => 'required',
                'donator_id' => 'required',
                'expires_at' => 'required',
                'receiver_id' => 'nullable',
                'status' => 'required',
            ]);


            $food = Food::where('uuid', '=', $id)->first();
            $this->authorize('verify', $food);
            if ($food) {
                $food->fill($validated);
                $food->save();

                FoodStatus::where('foods_uuid', '=', $id)->update([
                    'status' => $request->status
                ]);
            } else {
                return response()->json([
                    'message' => 'Food not found',
                ], 404);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 400);
        }
    }
    public function acceptFood(Request $request, string $id)
    {
        $food = Food::where('uuid', '=', $id)->first();
        $this->authorize('verify', $food);

        if ($food->receiver_id) {
            return response()->json([
                'message' => 'Food already has a receiver'
            ], 400);
        }

        $food->receiver_id = auth()->user()->uuid;
        $food->save();

        FoodStatus::where('foods_uuid', '=', $id)->update([
            'status' => 'accepted'
        ]);

        return response()->json([
            'message' => 'Food accepted successfully'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $food = Food::where('uuid', '=', $id)->first();

        $food->is_active = false;
        $food->save();

        return response()->json([
            'message' => 'Food deleted successfully'
        ], 200);
    }
}
