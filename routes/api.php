<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test-data', function () {
    return response()->json([
        'message' => 'សួស្តី! ទិន្នន័យនេះបានមកពី Laravel API ជោគជ័យហើយ។',
        'status' => 'success'
    ]);
});

Route::apiResource('categories', CategoryController::class);
Route::apiResource('products', ProductController::class);
