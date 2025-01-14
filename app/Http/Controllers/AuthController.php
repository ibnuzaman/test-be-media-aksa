<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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
    public function store(AuthRequest $request) : JsonResponse
    {
        try {
            $validated = $request->validated();
             
            if (!Auth::guard('admin')->attempt($validated)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'username or password is incorrect'
                ], 401);
            }

            $admin = Auth::guard('admin')->user();
            $data = Admin::where('username', $admin->username)->first();
            $token = $data->createToken('API_SECRET_TOKEN', ['*']);

            return response()->json([
                'status' => "success",
                'message' => 'Login successful',
                'data' => [
                    'token' => $token->plainTextToken,
                    'admin' => $data
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
    public function destroy(Request $request) : JsonResponse
    {
        try{
            $token = $request->user()->currentAccessToken()->delete();
            $request->user()->tokens->each(function ($token) {
                $token->delete();
            });
            if (!$token) {
                return response()->json(['error' => 'Unauthorized access'], 401);
            } 
            return response()->json([
                'status' => 'success',
                'message' => 'Logout successful'
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
