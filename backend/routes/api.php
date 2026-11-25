<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\CollectionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route CRUD Media
Route::apiResource('media', MediaController::class);

// Route User Collection
Route::get('/users/{userId}/collection', [CollectionController::class, 'index']);
Route::post('/users/{userId}/collection', [CollectionController::class, 'store']);
Route::delete('/users/{userId}/collection/{mediaId}', [CollectionController::class, 'destroy']);
