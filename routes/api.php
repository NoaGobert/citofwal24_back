<?php

use App\Http\Controllers\FoodCategoryController;
use App\Http\Controllers\FoodController;
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

Route::resource('/food', FoodController::class);
Route::resource('/food-category', FoodCategoryController::class);
