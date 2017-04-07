<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('auth/{provider}', 'SocialLoginController@redirectToProvider');

Route::get('auth/callback/{provider}', 'SocialLoginController@handleProviderCallback');

Route::get('/shops', 'ShopController@index');
Route::get('/shop/{id}', 'ShopController@viewShop');
Route::get('/search/{keyword}', 'ShopController@show');
Route::get('/shop-filter/{char}', "ShopController@shopFilter");

