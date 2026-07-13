<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;

class StaffsController extends Controller
{
    
    public function staffRoles()
    {
        return Inertia::render('MyFarmer/Admin/staffRoles');
    }

//     public function store(Request $request)
// {
//     $validated = $request->validate([
//         'name' => 'required|string|max:255',
//         'email' => 'required|email|unique:users,email',
//         'password' => 'required|confirmed|min:6',
//     ]);

//     $user = User::create([
//         'name' => $validated['name'],
//         'email' => $validated['email'],
//         'password' => Hash::make($validated['password']),
//     ]);

//     return response()->json([
//         'message' => 'User created successfully',
//         'user' => $user
//     ]);
// }


public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|confirmed|min:6',
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    return response()->json([
        'message' => 'User created successfully',
        'user' => $user
    ], 201);
}


}
