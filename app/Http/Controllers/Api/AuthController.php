<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Register
     *
     * Creates a new user account and returns a Sanctum bearer token.
     *
     * @group Authentication
     * @unauthenticated
     *
     * @bodyParam name string required The user's display name. Example: Max Mustermann
     * @bodyParam email string required The user's unique email address. Example: max@example.com
     * @bodyParam password string required The password. Must contain at least 8 characters, uppercase and lowercase letters, numbers, and symbols. Example: Secure123!
     *
     * @response 201 {
     *   "user": {
     *     "id": 1,
     *     "name": "Max Mustermann",
     *     "email": "max@example.com",
     *     "created_at": "2026-04-14T10:00:00.000000Z",
     *     "updated_at": "2026-04-14T10:00:00.000000Z"
     *   },
     *   "token": "1|plain-text-token"
     * }
     */
    public function register (Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => [
                'required',
                'string',
                Password::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols(),
            ],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Login
     *
     * Authenticates an existing user and returns a Sanctum bearer token.
     *
     * @group Authentication
     * @unauthenticated
     *
     * @bodyParam email string required The user's email address. Example: max@example.com
     * @bodyParam password string required The user's password. Example: Secure123!
     *
     * @response 200 {
     *   "user": {
     *     "id": 1,
     *     "name": "Max Mustermann",
     *     "email": "max@example.com",
     *     "created_at": "2026-04-14T10:00:00.000000Z",
     *     "updated_at": "2026-04-14T10:00:00.000000Z"
     *   },
     *   "token": "1|plain-text-token"
     * }
     *
     * @response 401 {
     *   "message": "Invalid credentials"
     * }
     */
    public function login (Request $request){
        $request->validate([
            'email' =>  ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }


        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * Get current user
     *
     * Returns the authenticated user's profile.
     *
     * @group Authentication
     * @authenticated
     *
     * @response 200 {
     *   "id": 1,
     *   "name": "Max Mustermann",
     *   "email": "max@example.com"
     * }
     */
    public function me(Request $request)
    {
        return response()->json(
            $request->user()->only(['id', 'name', 'email'])
        );
    }

    /**
     * Logout
     *
     * Deletes the current access token.
     *
     * @group Authentication
     * @authenticated
     *
     * @response 200 {
     *   "message": "Logged out"
     * }
     */
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out'
        ]);
    }
}
