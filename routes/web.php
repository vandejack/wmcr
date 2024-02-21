<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', 'LoginController@login')->name('login');
Route::post('/login', 'LoginController@login_validate');

Route::get('/reload-captcha', 'LoginController@reloadCaptcha')->name('reload-captcha');

Route::get('/auth-verification', 'LoginController@auth_verification')->name('auth-verification');
Route::post('/auth-verification', 'LoginController@login_post');

Route::get('/', function () {
    return view('layout');
});