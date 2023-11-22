<?php

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

Route::group([
    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', 'Api\AuthController@login');
    Route::post('/register', 'Api\AuthController@register');
    Route::post('/logout', 'Api\AuthController@logout');
    Route::post('/refresh', 'Api\AuthController@refresh');
    Route::get('/user-profile', 'Api\AuthController@userProfile');
    Route::post('/change-password', 'Api\AuthController@changePassWord');
    Route::post('/carts', 'Api\AuthController@addCarts');
    Route::get('/get-carts', 'Api\AuthController@getCarts');
    Route::middleware(['checkAdmin'])->group(function () {
        // Áp dụng middleware 'checkAdmin' chỉ cho tuyến đường '/user-admin'
        Route::get('/user-admin', 'Api\AuthController@userAdmin');
    });
});

// /api/auth/

// api route của order
Route::group([
    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'order'
], function ($router) {
    Route::middleware(['checkAdmin'])->group(function () {
        // Áp dụng middleware 'checkAdmin' chỉ cho tuyến đường '/user-admin'
        Route::get('/admin/', 'Api\OrderController@index');
        Route::get('/admin/all', 'Api\OrderController@paginationPage');
        Route::get('/admin/search/{id}', 'Api\OrderController@search');
        Route::get('/admin/get/total', 'Api\OrderController@getTotal');
        Route::get('/admin/get/{id}', 'Api\OrderController@show');
        Route::put('/admin/update/{id}', 'Api\OrderController@update');
        Route::delete('/admin/delete/{id}', 'Api\OrderController@delete');
    });

    Route::middleware(['checkUser'])->group(function () {
        Route::get('/user/', 'Api\OrderController@index');
        Route::get('/user/all', 'Api\OrderController@paginationPage');
        Route::get('/user/search/{id}', 'Api\OrderController@search');
        Route::get('/user/get/total', 'Api\OrderController@getTotal');
        Route::get('/user/get/{id}', 'Api\OrderController@show');
    });
});
