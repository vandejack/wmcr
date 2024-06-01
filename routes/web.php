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

    Route::get('/profile', 'EmployeeController@profile')->name('profile');
    Route::post('/profile', 'EmployeeController@profile_post');

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

    Route::prefix('dashboard')->group(function () {
        Route::get('/TicketsMonitoring', 'DashboardController@TicketsMonitoring');
        Route::get('/TicketsMonitoringList/{witel}/{status}', 'DashboardController@TicketsMonitoringList');
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

    Route::prefix('order')->group(function () {
        Route::get('/ticket/{id}', 'OrderController@ticket');
        Route::post('/ticket/{id}', 'OrderController@ticket_post');
        
        Route::get('/search', 'OrderController@search');
        Route::post('/search', 'OrderController@search_post');

        Route::get('/matrix', 'OrderController@matrix');
        Route::post('/matrix', 'OrderController@matrix_post');

        Route::get('/undispatch', 'OrderController@undispatch');
        Route::post('/undispatch', 'OrderController@undispatch_post');

        Route::get('/undispatch-detail', 'OrderController@undispatch_detail');
    });

    Route::prefix('ajax')->group(function () {
        Route::get('/master/{id}', 'AjaxController@master_data');
        Route::get('/employee/{id}', 'AjaxController@employee_data');
        Route::get('/sector/{id}', 'AjaxController@sector_data');

        Route::get('/select2/{id}/{x}', 'AjaxController@select_data');

        Route::prefix('order')->group(function () {
            Route::get('/undispatch/{start_date}/{end_date}', 'AjaxController@undispatch_order');
            Route::get('/undispatch-detail', 'AjaxController@undispatch_detail');
            Route::get('/undispatch-search/{order}/{id}', 'AjaxController@undispatch_search');
        });

        Route::prefix('dashboard')->group(function () {
            Route::get('/ttr-hvc', 'AjaxController@trr_hvc');
        });
    });
});

Route::get('/logout', 'LoginController@logout')->name('logout');