<?php

use App\Http\Controllers\api\auth\AuthController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::get('user', [AuthController::class, 'index']);
Route::middleware('auth:sanctum')->get('ShowUser', [AuthController::class, 'ShowUser']);

// product
Route::get('product', [ProductController::class, 'index']);
Route::post('product/add', [ProductController::class, 'store']);
Route::get('product/{id}', [ProductController::class, 'show']);
Route::put('product/edit/{id}', [ProductController::class, 'update']);
Route::delete('product/delete/{id}', [ProductController::class, 'delete']);

//category
Route::get('category', [CategoriesController::class, 'index']);
Route::post('category/add', [CategoriesController::class, 'store']);
Route::get('category/{id}', [CategoriesController::class, 'show']);
Route::put('category/edit/{id}', [CategoriesController::class, 'update']);
Route::delete('category/delete/{id}', [CategoriesController::class, 'delete']);
