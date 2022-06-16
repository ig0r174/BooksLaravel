<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::prefix('/book')->group(function () {
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('/', ['uses' => 'App\Http\Controllers\BookController@getAll']);
        Route::get('/{id}', ['uses' => 'App\Http\Controllers\BookController@get']);
        Route::post('/', ['uses' => 'App\Http\Controllers\BookController@create']);
        Route::delete('/{id}', ['uses' => 'App\Http\Controllers\BookController@delete']);
        Route::put('/{id}', ['uses' => 'App\Http\Controllers\BookController@edit']);
    });
});

Route::prefix('/bookStatuses')->group(function () {
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('/', ['uses' => 'App\Http\Controllers\BookStatusController@getAll']);
        Route::get('/{id}', ['uses' => 'App\Http\Controllers\BookStatusController@get']);
    });
});

Route::prefix('/user')->group(function () {

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('/info', ['uses' => 'App\Http\Controllers\AuthController@getCurrentUser'])->name('curuserinfo');
        Route::get('/info/{id}', ['uses' => 'App\Http\Controllers\AuthController@getAnotherUser'])->name('othuserinfo');
        Route::post('/logout', ['uses' => 'App\Http\Controllers\AuthController@logout']);
        Route::put('/edit', ['uses' => 'App\Http\Controllers\AuthController@edit']);
    });

    Route::post('/register', ['uses' => 'App\Http\Controllers\AuthController@register'])->name('reguser');
    Route::post('/login', ['uses' => 'App\Http\Controllers\AuthController@login'])->name('loginpage');

});

Route::prefix('/userRoles')->group(function () {
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('/', ['uses' => 'App\Http\Controllers\UserRolesController@getAll']);
        Route::get('/{id}', ['uses' => 'App\Http\Controllers\UserRolesController@get']);
    });
});

Route::prefix('/cabinet')->group(function () {
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/', ['uses' => 'App\Http\Controllers\BookUserRelationController@create']);
        Route::get('/getAllByUser', ['uses' => 'App\Http\Controllers\BookUserRelationController@getAllByUser']);
        Route::get('/getCoworkersOfBook', ['uses' => 'App\Http\Controllers\BookUserRelationController@getAllOfBook']);
    });
});
