<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\UsersGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GroupsController extends Controller
{
    public function index()
    {
        $groups = Group::where('is_active', true)->get();

        return response()->json([
            $groups
        ], 200);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required',
                'description' => 'required',
                'group_owner' => 'required|exists:users,uuid'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors()
            ], 422);
        }

        $group = group::create([
            'uuid' => Str::uuid(),
            'name' => $request->name,
            'description' => $request->description,
            'group_owner' => $request->group_owner,

        ]);

        UsersGroup::create([
            'user_uuid' => $request->group_owner,
            'group_uuid' => $group->uuid
        ]);

        return response()->json([
            'message' => 'Group created successfully',
            'group' => $group
        ], 201);
    }

    public function show($id)
    {
        $group = Group::where('id', $id)->where('is_active', true)->first();

        if (!$group) {
            return response()->json([
                'message' => 'Group not found'
            ], 404);
        }

        return response()->json([
            'group' => $group
        ], 200);
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required',
                'description' => 'required',
                'group_owner' => 'required,exists:users,id'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors()
            ], 422);
        }

        $group = Group::where('id', $id)->where('is_active', true)->first();

        if (!$group) {
            return response()->json([
                'message' => 'Group not found'
            ], 404);
        }

        Group::where('id', $id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'group_owner' => $request->group_owner
        ]);

        return response()->json([
            'message' => 'Group updated successfully',
            'group' => $group
        ], 200);
    }

    public function destroy($id)
    {
        $group = Group::where('id', $id)->where('is_active', true)->first();

        if (!$group) {
            return response()->json([
                'message' => 'Group not found'
            ], 404);
        }

        $group->is_active = false;
        $group->save();

        return response()->json([
            'message' => 'Group deleted successfully'
        ], 200);
    }
}