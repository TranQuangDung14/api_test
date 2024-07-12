<?php

use App\Http\Controllers\Api\auth\AuthController;
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
Route::get('Notlogin', [AuthController::class, 'Notlogin'])->name('Notlogin');
Route::get('users', [AuthController::class, 'index'])->middleware('auth:sanctum');
Route::prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::get('ShowUser', [AuthController::class, 'ShowUser']);
    Route::put('editfullname', [AuthController::class, 'editfullname']);
    Route::post('create', [AuthController::class, 'create']);
    Route::get('show/{id}', [AuthController::class, 'Show']);
    Route::put('edit', [AuthController::class, 'edit']);
    Route::delete('delete/{id}', [AuthController::class, 'delete']);
    Route::post('editavatar', [AuthController::class, 'editavatar']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    // Route::post('/password/forgot', [ForgotPasswordController::class, 'sendResetLinkEmail'])->withoutMiddleware('auth:sanctum');
    
});
Route::post('password/forgot', [AuthController::class, 'sendResetEmail']);
Route::post('password/reset', [AuthController::class, 'reset']);


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

Route::get('/send-mail', [ProductController::class, 'sendMail']);
Route::get('/veryfy-account/{email}', [ProductController::class, 'index']);
