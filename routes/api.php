<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\EmployeeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/failure', function () {
    return response()->json([
        'status' => false,
        'message' => 'Unauthorized access'
    ], 401);
})->name('login');

Route::post('/login', [AuthController::class, 'store']);

Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/divisions', [DivisionController::class, 'index']);
    Route::post('/employees', [EmployeeController::class, 'store']);
    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::put('/employees/{id}', [EmployeeController::class, 'update']);
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);
});

