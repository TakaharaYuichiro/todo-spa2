<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['firebase'])->group(function () {
  Route::get('/user', function (Request $request) {
    return response()->json(['firebase_user_id' => $request->attributes->get('firebase_user_id')]);
  });

  Route::get('/users', [AuthController::class, 'getUsers']);
  Route::apiResource('/todo', TodoController::class);
  Route::apiResource('/category', CategoryController::class);  
});

// Route::post('/username', [AuthController::class, 'getUserName']);
// Route::get('/usercheck', [AuthController::class, 'getUserName']);


Route::get('/usercheck', [AuthController::class, 'getUserName'])->missing(function (Request $request) {
  return response()->json(['message' => 'User not found'], 404);
});


