<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::get('products', [ProductApiController::class, 'index']);
Route::get('products/{product}', [ProductApiController::class, 'show']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('products', [ProductApiController::class, 'store']);
    Route::put('products/{product}', [ProductApiController::class, 'update']);
    Route::delete('products/{product}', [ProductApiController::class, 'destroy']);
});
