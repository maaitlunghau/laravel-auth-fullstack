<?php

use App\Http\Controllers\api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// version có authentication
// Route::prefix('users')->middleware('auth:sanctum')->group(function () {
//     Route::get('/', [UserController::class, 'index']);
//     Route::get('/{user}', [UserController::class, 'show']);
//     Route::post('/', [UserController::class, 'store']);
//     Route::put('/{user}', [UserController::class, 'update']);
//     Route::delete('/{user}', [UserController::class, 'destroy']);
//     Route::patch('/{user}/ban', [UserController::class, 'ban']);
//     Route::patch('/{user}/unban', [UserController::class, 'unban']);
// });

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{user}', [UserController::class, 'show']);
    Route::post('/', [UserController::class, 'store']);
    Route::put('/{user}', [UserController::class, 'update']);
    Route::delete('/{user}', [UserController::class, 'destroy']);
    Route::patch('/{user}/ban', [UserController::class, 'ban']);
    Route::patch('/{user}/unban', [UserController::class, 'unban']);
});
