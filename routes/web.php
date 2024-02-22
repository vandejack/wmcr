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


Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'HomeController@index')->name('home');

    Route::get('/profile', 'HomeController@profile')->name('profile');

    Route::prefix('master')->group(function () {
        Route::get('/regional', 'MasterController@regional');
        Route::get('/witel', 'MasterController@witel');
        Route::get('/sto', 'MasterController@sto');
        Route::get('/sto/edit/{id}', 'MasterController@sto');
        Route::get('/mitra', 'MasterController@mitra');
        Route::get('/level', 'MasterController@level');
    });

    Route::prefix('employee')->group(function () {
        Route::get('/', 'EmployeeController@index');
        Route::get('/edit/{id}', 'EmployeeController@edit');
        Route::get('/unit', 'EmployeeController@unit');
        Route::get('/sub-unit', 'EmployeeController@sub_unit');
        Route::get('/sub-group', 'EmployeeController@sub_group');
        Route::get('/position', 'EmployeeController@position');
    });

    Route::prefix('sector')->group(function () {
        Route::get('/', 'SectorController@index');
        Route::get('/rayon', 'SectorController@rayon');
        Route::get('/team', 'SectorController@team');
        Route::get('/alpro', 'SectorController@alpro');
        Route::get('/schedule', 'SectorController@schedule');
        Route::get('/brifieng', 'SectorController@brifieng');
        Route::get('/alker', 'SectorController@alker');
    });
});

Route::get('/logout', 'LoginController@logout')->name('logout');