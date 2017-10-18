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

Route::get('/', 'HomeController@index');

Route::POST('/startWork', 'HomeController@startWork');
Route::POST('/stopWork', 'HomeController@stopWork');
Route::POST('/register_hour','WorkHoursController@registerHour');
Route::POST('/datatableAcceptHour','WorkHoursController@datatableAcceptHour')->name('api.acceptHour');
Route::POST('/saveAcceptHour','WorkHoursController@saveAcceptHour')->name('api.saveAcceptHour');

Route::POST('/deleteAcceptHour','WorkHoursController@deleteAcceptHour')->name('api.deleteAcceptHour');
Route::POST('/editAcceptHour','WorkHoursController@editAcceptHour')->name('api.editAcceptHour');



Auth::routes();

Route::middleware(['check-permission'])->group(function () {


    // Work_hours --Start--
    Route::get('/accept_hour','WorkHoursController@acceptHour');
    Route::get('/add_hour','WorkHoursController@addHour');
    Route::get('/view_hour','WorkHoursController@viewHourGet');
    Route::Post('/view_hour','WorkHoursController@viewHourPost');
    // Work_hours --end--

});


