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
Route::post('/telegram/webhook', 'TelegramController@handleWebhook');
Route::get('/telegram/test', 'TelegramController@testz');
Route::get('/mapcalculate', 'IhldController@adjustCoordinates');
Route::get('/login', 'LoginController@login')->name('login');
Route::post('/login', 'LoginController@login_validate');

Route::get('/reload-captcha', 'LoginController@reloadCaptcha')->name('reload-captcha');

Route::get('/auth-verification', 'LoginController@auth_verification')->name('auth-verification');
Route::post('/auth-verification', 'LoginController@login_post');


Route::group(['middleware' => 'auth'], function () {

    Route::get('/', 'HomeController@index')->name('home');
    Route::post('/save-location','HomeController@saveLocation');

    Route::get('/profile', 'EmployeeController@profile')->name('profile');
    Route::post('/profile', 'EmployeeController@profile_post');

    Route::prefix('ihld')->group(function () {
        Route::get('/uploadForm', 'IhldController@uploadForm');
        Route::post('/uploadForm', 'IhldController@upload');
        Route::get('/preview', 'IhldController@preview');
    });

    Route::prefix('master')->group(function () {
        Route::get('/regional', 'MasterController@regional');
        Route::get('/witel', 'MasterController@witel');
        Route::get('/sto', 'MasterController@sto');
        Route::get('/sto/edit/{id}', 'MasterController@sto');
        Route::get('/mitra', 'MasterController@mitra');
        Route::get('/level', 'MasterController@level');
        Route::get('/importODPPreview', 'MasterController@importODPPreview');
        Route::get('/odpUpdate', 'MasterController@odpUpdate');
        Route::post('/odpUpdate', 'MasterController@importData');
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
        Route::get('/mapAlpro/{witel}','SectorController@mapAlpro');
        Route::get('/odpSector/{witel}','SectorController@odpSector');
        Route::post('/mapAlpro/{witel}','SectorController@saveOdpSector');
    });

    Route::prefix('schedule')->group(function () {
        Route::get('/manage/{id}/{periode}', 'SchedulingController@manage');
        Route::get('/list/{sector}/{status}/{periode}/{ishold}/{approval}', 'SchedulingController@list');
        Route::get('/approval/{id}/{status}','SchedulingController@approval');
        Route::get('/update/{id}','SchedulingController@update');
        Route::post('/update/{id}','SchedulingController@updatePost');
        Route::get('/trial','SchedulingController@trial');
    });

    Route::prefix('order')->group(function () {
        Route::get('/assign/{id}','OrderController@assign'); 
        Route::get('/basket/{witel}/{sektor}/{periode}', 'OrderController@basket');
        Route::get('/basket/list/{order_type}/{witel}/{sektor}', 'OrderController@basketList');
        Route::get('/ajaxBasket/{order_type}/{sektor}', 'OrderController@ajaxBasket');
        Route::get('/sync_to_basket/{order_type}', 'OrderController@sync_to_basket');
        Route::get('/ticket/{id}', 'OrderController@ticket');
        Route::get('/dispatchManual/{id}/{periode}','OrderController@dispatchManual');
        Route::post('/dispatchManual/{id}/{periode}','OrderController@dispatchSave');
        Route::post('/dispatchSave','OrderController@dispatchSave');
        Route::post('/ticket/{id}', 'OrderController@ticket_post');
        Route::get('/dispatchAjax/{teamID}/{date}/{start}/{end}/{orderID}','OrderController@dispatchAjax');
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
        Route::get('/sector/{type}/{id}', 'AjaxController@sector_data');
        Route::get('/getSector/{id}','AjaxController@getSector');
        Route::get('/select2/{id}/{x}', 'AjaxController@select_data');

        Route::prefix('order')->group(function () {
            Route::get('/undispatch/{start_date}/{end_date}', 'AjaxController@undispatch_order');
            Route::get('/undispatch-detail', 'AjaxController@undispatch_detail');
            Route::get('/undispatch-search/{order}/{id}', 'AjaxController@undispatch_search');
        });

        Route::prefix('dashboard')->group(function () {
            Route::get('/productivity-order', 'AjaxController@productivity_order');
            Route::get('/ttr-comply-notcomply-open', 'AjaxController@ttr_comply_notcomply_open');
            Route::get('/ttr-comply-notcomply-closed', 'AjaxController@ttr_comply_notcomply_closed');
            Route::get('/productivity-provisioning', 'AjaxController@dashboard_produktif');
        });
    });
    Route::prefix('tech')->group(function(){
        Route::get('/home','TechController@home');
        Route::get('/orderview/{id}','TechController@orderView');
        Route::post('/orderview/{id}','TechController@saveProv');
        Route::get('/startProgress/{id}','TechController@startProgress');
        Route::get('/absensi','TechController@absensi');
        Route::get('/requestApproval','TechController@requestApproval');
        Route::get('/location/{id}','TechController@location');
    });

    Route::get('/grab/starclick/{witel}','GrabController@WitelStarclicktoBasket');
});

Route::get('/logout', 'LoginController@logout')->name('logout');