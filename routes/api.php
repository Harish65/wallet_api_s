<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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
Route::post('/login', [AuthController::class, 'userLogin']);;
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     // return $request->user();
//     // Route::post('deposite',     'API\UsersController@deposite');
    
// });
Route::middleware('auth:api')->group( function () {
    Route::post('deposite','App\Http\Controllers\UsersController@deposite');
    Route::post('buyCookies','App\Http\Controllers\UsersController@buyCookies');
});