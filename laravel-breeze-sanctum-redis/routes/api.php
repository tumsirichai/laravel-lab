<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
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

Route::prefix('v1')->group(function () {
    Route::get('/dashboard', function () {
        return 'api dashboard';
    });
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/register', [AuthController::class, 'register']);

    Route::middleware(['auth:sanctum','role.admin'])->group(function () {
        Route::get('/users', function () {
            return User::all();
        });
    });    
    Route::middleware(['auth:sanctum','role.user'])->group(function () {
        Route::get('/products', function () {
            return response()->json([
                '0' => ['name'=>'GAP Girls\' Femme T-Shirt','price'=> 500.00],
                '1' => ['name'=>'GAP Girls\' 3-Pack Cartwheel Shorts','price'=> 5200.00],
            ]);
        });
    });    

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        // Route::get('/users', [PostController::class, 'show'])->middleware('restrictRole:admin');
        // Route::put('/users/{id}', [PostController::class, 'update'])->middleware('restrictRole:moderator');
    });

    // - playground lab
    Route::prefix('lab')->group(function () {
        Route::post('/redis', function (Request $request) {
            Redis::set($request->key, $request->value);
        });
        Route::get('/redis/{key}', function (Request $request) {
            return Redis::get($request->key);
        });
        Route::delete('/redis/{key}', function (Request $request) {
            Redis::del($request->key);
        });
    });
});

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


