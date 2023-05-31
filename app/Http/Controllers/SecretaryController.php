<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;


class SecretaryController extends Controller
{
    public function index()
    {
        // Retrieve all users with the secretary role
        $secretaries = User::role('secretary')->get();

        return response()->json([
            'secretaries' => $secretaries,
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // Create the user
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        // Assign the secretary role to the user
        $secretaryRole = Role::where('name', 'secretary')->first();
        $user->assignRole($secretaryRole);

        return response()->json([
            'status' => 'Request was successful.',
            'message' => null,
            'data' => [
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'updated_at' => $user->updated_at,
                    'created_at' => $user->created_at,
                    'id' => $user->id,
                ],
                'role' => $secretaryRole->name,
                'token' => $user->createToken('API Token')->plainTextToken,
            ],
        ]);
    }

    public function update(Request $request, User $secretary)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($secretary->id),
            ],
            'password' => 'nullable|string|min:8',
        ]);

        $secretary->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password') ? bcrypt($request->input('password')) : $secretary->password,
        ]);

        $secretaryRole = Role::where('name', 'secretary')->first();

        return response()->json([
            'status' => 'Request was successful.',
            'message' => null,
            'data' => [
                'user' => [
                    'name' => $secretary->name,
                    'email' => $secretary->email,
                    'updated_at' => $secretary->updated_at,
                    'created_at' => $secretary->created_at,
                    'id' => $secretary->id,
                ],
                'role' => $secretaryRole->name,
                'token' => $secretary->createToken('API Token')->plainTextToken,
            ],
        ]);
    }

    public function destroy(User $secretary)
    {
        $secretary->delete();

        return response()->json([
            'message' => 'Secretary deleted successfully.',
        ]);
    }
}
