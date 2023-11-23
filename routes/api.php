<?php

use App\Http\Controllers\Api\CheckOutController;
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
    // Route::post('/carts', 'Api\AuthController@addCarts');
    // Route::get('/get-carts', 'Api\AuthController@getCarts');
    Route::middleware(['checkAdmin'])->group(function () {
        // Áp dụng middleware 'checkAdmin' chỉ cho tuyến đường '/user-admin'
        Route::get('/user-admin', 'Api\AuthController@userAdmin');
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
    });
});

Route::group([
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'cart'
], function ($router) {
    // middleware checkRole dùng để check xem thành viên đã đăng nhập hay chưa
    Route::middleware(['checkRole']) -> group(function () {
        Route::get('/get-carts', 'Api\CartController@getCarts');
        Route::post('/add-to-cart', 'Api\CartController@addToCart');
        Route::put('/update-quantity', 'Api\CartController@updateQuantity');
        Route::delete('/delete-item/{cartdetailsid}', 'Api\CartController@deleteCartItem');
        Route::delete('/delete-list', 'Api\CartController@deleteCartItemList');
    });
});

Route::middleware(['checkRole']) -> group(function () {
     Route::post('/check-out', [CheckOutController::class, 'checkOut']);
});