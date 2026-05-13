<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Get users
     *
     * Returns all users with public profile fields.
     *
     * @group Users
     * @authenticated
     *
     * @response 200 [
     *   {
     *     "id": 1,
     *     "name": "Max Mustermann",
     *     "email": "max@example.com"
     *   }
     * ]
     */
    public function index() {
        return response()->json(
            User::select('id', 'name', 'email')->get()
        );
    }
}
