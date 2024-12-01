<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return response()->json(['roles' => $roles]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles,name',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $role = Role::create($request->all());
        return response()->json(['role' => $role], 201);
    }

    public function show(Role $role)
    {
        return response()->json(['role' => $role]);
    }

    public function update(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $role->update($request->all());
        return response()->json(['role' => $role]);
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json(['message' => 'Role deleted successfully']);
    }

    // Kullanıcıya rol atama
    public function assignRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::find($request->user_id);
        $role = Role::find($request->role_id);

        $user->roles()->syncWithoutDetaching([$role->id]);
        return response()->json(['message' => 'Role assigned successfully']);
    }

    // Kullanıcıdan rol kaldırma
    public function removeRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::find($request->user_id);
        $role = Role::find($request->role_id);

        $user->roles()->detach($role->id);
        return response()->json(['message' => 'Role removed successfully']);
    }

    // Kullanıcının rollerini getirme
    public function getUserRoles($userId)
    {
        $user = User::findOrFail($userId);
        return response()->json(['roles' => $user->roles]);
    }
}
