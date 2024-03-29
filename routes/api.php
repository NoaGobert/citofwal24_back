<?php

use App\Http\Controllers\FoodCategoryController;
use App\Http\Controllers\FoodController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\UsersController;

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








Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/validate-token', [AuthController::class, 'validateToken']);

Route::group(['middleware' => 'auth:sanctum'], function () {

    // AUTH
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/authenticated-user', [AuthController::class, 'authenticatedUser']);
    Route::get('/profil', [AuthController::class, 'profil']);

    // FOODS
    Route::resource('/food', FoodController::class);
    Route::resource('/food-category', FoodCategoryController::class);
    Route::post('/food/accept/{id}', [FoodController::class, 'acceptFood']);

    // USERS
    Route::resource('/users', UsersController::class);

    //GROUPS
    Route::resource('/groups', GroupsController::class);
});