<?php

namespace App\Http\Controllers;

use App\Models\food;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $foods = Food::where('is_active', '=', true)->get();

        return $foods;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required',
                'description' => 'required',
                'diet' => 'required',
                'food_category_id' => 'required',
                'donator_id' => 'required',
                'expires_at' => 'required',
                'receiver_id' => 'nullable'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 400);
        }

        $food = Food::create($validated);

        return $food;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $food = Food::where('uuid', '=', $id)->first();

        return $food;
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
                'receiver_id' => 'nullable'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
