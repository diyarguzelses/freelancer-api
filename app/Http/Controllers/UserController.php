<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json($user, 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }

    public function index()
    {
        return User::all();
    }

    public function show($id)
    {
        return User::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'surname' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->update($request->except('password') + ['password' => $user->password]);

        return response()->json($user, 200);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(null, 204);
    }
    public function updateIsFreelancer(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'is_freelancer' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user->is_freelancer = $request->is_freelancer;
        $user->save();

        return response()->json($user, 200);
    }

    public function updateIsAdmin(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'is_admin' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user->is_admin = $request->is_admin;
        $user->save();

        return response()->json($user, 200);
    }

    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user->status = $request->status;
        $user->save();

        return response()->json($user, 200);
    }

    public function updateDescription(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->status != 1) {
            return response()->json(['error' => 'User not approved'], 403);
        }

        $validator = Validator::make($request->all(), [
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user->description = $request->description;
        $user->save();

        return response()->json($user, 200);
    }
}

