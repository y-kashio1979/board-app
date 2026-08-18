<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ThreadController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/threads', [ThreadController::class, 'index']);
Route::post('/threads/create', [ThreadController::class, 'store']);
Route::get('/threads/{thread}', [ThreadController::class, 'show']);
Route::put('/threads/{thread}', [ThreadController::class, 'update']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('auth/me', [AuthController::class, 'me']);
    Route::post('/threads/{thread}/posts', [CommentController::class, 'store']);
});
