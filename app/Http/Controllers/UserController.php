<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getAllUsers()
    {
        $users = User::select('id', 'name', 'email', 'created_at', 'updated_at')->get();

        return response()->json(['users' => $users], 200);
    }
}
