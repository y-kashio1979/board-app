<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ThreadController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/threads/{thread}', [ThreadController::class, 'show']);
Route::post('/threads/create', [ThreadController::class, 'store']);