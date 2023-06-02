<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;


class SecretaryController extends Controller
{
    public function index(Request $req)
    {
        $query =  $req->cin;
        $skip = $req->skip;
        $take = $req->take;
        // Retrieve all users with the secretary role
        $userQuery = User::role('secretary');

        if (!empty($query)) {
            $userQuery->where('cin', 'like', $query . '%');
        }


        return response()->json([
            'data' => $userQuery->skip($skip)->take($take)->get(),
            'total' => $userQuery->count()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'cin' => 'required|string|unique:users',
            'Password' => 'required|string|min:8',
            'Password_Confirmation' => 'required|string|min:8',
        ]);

        // Create the user
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'cin' => $request->input('cin'),
            'password' => $request->Password,
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
                    'cin' => $user->cin,
                    'updated_at' => $user->updated_at,
                    'created_at' => $user->created_at,
                    'id' => $user->id,
                ],
                'role' => $secretaryRole->name,
                'token' => $user->createToken('API Token')->plainTextToken,
            ],
        ]);
    }

    public function update(Request $request, $id)
    {
        $secretary = User::find($id);
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
            'cin' => $request->input('cin'),
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
                    'cin' => $secretary->cin,
                ],
                'role' => $secretaryRole->name,
                'token' => $secretary->createToken('API Token')->plainTextToken,
            ],
        ]);
    }

    public function destroy($id)
    {

        $sec = User::find($id);
        $sec->delete();
        return response()->json([
            'message' => 'Secretary deleted successfully.',
        ]);
    }
}
