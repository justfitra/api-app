<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register', 'AuthController@register')->name('register');
Route::post('/login', 'AuthController@login')->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', 'AuthController@logout');
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::apiResource('user', 'UserController');
});
