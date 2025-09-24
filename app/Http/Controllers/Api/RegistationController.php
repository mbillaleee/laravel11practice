<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegistationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function register(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => 'Validation failed',
                'data' => $validator->errors()->all()
            ], 422);
        }

        // ✅ Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $response = [];
        $response["token"] = $user->createToken("MyApp")->plainTextToken;
        $response['name'] = $user->name;
        $response['email'] = $user->email;

        // ✅ Return JSON response
        return response()->json([
            'status' => 1,
            'message' => 'User created successfully',
            'data' => $response,
        ], 201);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function login(Request $request)
    {
        // ✅ Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // ✅ Attempt login
        if (Auth::attempt(["email" => $request->email, "password" => $request->password])) {
            $user = Auth::user();

            $response = [];
            $response["token"] = $user->createToken("MyApp")->plainTextToken;
            $response['name'] = $user->name;
            $response['email'] = $user->email;

            return response()->json([
                'status' => 1,
                'message' => 'Login successful',
                'data' => $response,
            ], 200);
        }

        // ❌ Authentication failed
        return response()->json([
            'status' => 0,
            'message' => 'Authentication error. Invalid credentials',
            'data' => null,
        ], 401);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
