<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\CollectionController;
use App\Http\Controllers\Api\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route CRUD Media
Route::apiResource('media', MediaController::class);

// Route User Collection
Route::get('/users/{userId}/collection', [CollectionController::class, 'index']);
Route::post('/users/{userId}/collection', [CollectionController::class, 'store']);
Route::delete('/users/{userId}/collection/{mediaId}', [CollectionController::class, 'destroy']);

// Route User
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{id}', [UserController::class, 'show']);
