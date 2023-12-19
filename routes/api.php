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
    Route::post('/change-password', 'Api\AuthController@changePassword');
    Route::post('/carts', 'Api\AuthController@addCarts');
    Route::get('/get-carts', 'Api\AuthController@getCarts');
    Route::middleware(['checkAdmin'])->group(function () {
        // Áp dụng middleware 'checkAdmin' chỉ cho tuyến đường '/user-admin'
        Route::get('/user-admin', 'Api\AuthController@userAdmin');
        Route::get('/get-all', 'Api\AuthController@getAllUser');
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


Route::group([
    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'dogitem'
], function ($router) {
    Route::get('/', 'Api\DogItemController@list');
    Route::get('/get/{id}', 'Api\DogItemController@getDogById');
    Route::get('/search/{name}', 'Api\DogItemController@getDogByName');
    Route::get('/all', 'Api\DogItemController@paginationPage');
    Route::get('/testRelationship', 'Api\DogItemController@testRelationship');
    Route::middleware(['checkAdmin'])->group(function () {
        // Áp dụng middleware 'checkAdmin' chỉ cho tuyến đường '/user-admin'
        Route::post('/create', 'Api\DogItemController@create');
        Route::put('/update/{id}', 'Api\DogItemController@update');
        Route::delete('/delete/{id}', 'Api\DogItemController@delete');
        Route::get('/all-admin', 'Api\DogItemController@paginationPageAdmin');
        Route::get('/detail-admin/{id}', 'Api\DogItemController@getDogByIdAdmin');
    });
});

Route::group([
    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'item'
], function ($router) {
    Route::get('/','Api\ItemController@list');
    Route::get('/get/{id}','Api\ItemController@getProductbyId');
    Route::get('/search/{name}','Api\ItemController@getProductbyName');
    Route::get('/all', 'Api\ItemController@paginationPage');
    Route::middleware(['checkAdmin'])->group(function () {
        // Áp dụng middleware 'checkAdmin' chỉ cho tuyến đường '/user-admin'
        Route::post('/create','Api\ItemController@create');
        Route::put('/update/{id}','Api\ItemController@update');
        Route::delete('/delete/{id}','Api\ItemController@delete');
        Route::get('/get-admin', 'Api\ItemController@paginationPageAdmin');
        Route::get('/getdetail-admin/{id}', 'Api\ItemController@getDogByIdAdmin');
    });
});

Route::group([
    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'checkout'
], function ($router) {
    Route::post('/', 'Api\CheckoutController@create');
    Route::get('/list/{user_id}', 'Api\CheckoutController@getByUser');
    Route::get('/detail/{id}', 'Api\CheckoutController@getById');
    Route::post('/vnpay', 'Api\CheckoutController@checkoutVnp');
    Route::middleware(['checkAdmin'])->group(function () {
        // Áp dụng middleware 'checkAdmin' chỉ cho tuyến đường '/user-admin'
        Route::get('/get-all', 'Api\CheckoutController@getAll');
        Route::put('/update/{id}', 'Api\CheckoutController@update');
    });
});

Route::group([
    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'email'
], function ($router) {
    Route::post('/send', 'Api\SendEmailController@checkout');
});