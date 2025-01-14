<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string|max:255',
                'password' => 'required|string|min:6',
            ]);

            if (!Auth::attempt($validated)) {
                return response()->json([
                    'message' => 'ussername or password is incorrect'
                ], 401);
            }

            $user = $request->user();
            $token = $user->createToken('API_SECRET_TOKEN', ['*']);

            return response()->json([
                'status' => "success",
                'message' => 'Login successful',
                'data' => [
                    'token' => $token->plainTextToken,
                    'admin' => [
                        'id' => $request->user()->id,
                        'name' => $request->user()->name,
                        'username' => $request->user()->username,
                        'phone' => $request->user()->phone,
                        'email' => $request->user()->email,
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
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
