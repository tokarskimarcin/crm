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


//********************AJAX*********************** */
Route::POST('/datatableAcceptHour','WorkHoursController@datatableAcceptHour')->name('api.acceptHour');
Route::POST('/datatableAcceptHourCadre','WorkHoursController@datatableAcceptHourCadre')->name('api.acceptHourCadre');
Route::POST('/datatableCheckList','WorkHoursController@datatableCheckList')->name('api.checkList');
Route::POST('/saveAcceptHour','WorkHoursController@saveAcceptHour')->name('api.saveAcceptHour');
Route::POST('/deleteAcceptHour','WorkHoursController@deleteAcceptHour')->name('api.deleteAcceptHour');
Route::POST('/editAcceptHour','WorkHoursController@editAcceptHour')->name('api.editAcceptHour');
Route::POST('/addAcceptHour','WorkHoursController@addAcceptHour')->name('api.addAcceptHour');


Route::POST('/uniqueUsername','UsersController@uniqueUsername')->name('api.uniqueUsername');
Route::POST('/datatableEmployeeManagement','UsersController@datatableEmployeeManagement')->name('api.datatableEmployeeManagement');

Route::POST('/datatableCadreManagement','UsersController@datatableCadreManagement')->name('api.datatableCadreManagement');

Route::POST('/datatableDkjRaport','DkjController@datatableDkjRaport')->name('api.datatableDkjRaport');
Route::POST('/getUser','DkjController@getUser')->name('api.getUser');
Route::POST('/dkjRaportSave','DkjController@dkjRaportSave')->name('api.dkjRaportSave');

Route::POST('/datatableDkjVerification','DkjController@datatableDkjVerification')->name('api.datatableDkjVerification');
Route::POST('/saveDkjVerification','DkjController@saveDkjVerification')->name('api.saveDkjVerification');
Route::POST('/datatableShowDkjVerification','DkjController@datatableShowDkjVerification')->name('api.datatableShowDkjVerification');

Route::POST('/datatableShowUserSchedule','ScheduleController@datatableShowUserSchedule')->name('api.datatableShowUserSchedule');
Route::POST('/saveSchedule','ScheduleController@saveSchedule')->name('api.saveSchedule');


Route::POST('/saveSummaryPayment','FinancesController@saveSummaryPayment')->name('api.summary_payment_save');
Route::POST('/editPenaltyBonus','FinancesController@editPenaltyBonus')->name('api.editPenaltyBonus');

Route::POST('/deletePenaltyBonus','FinancesController@deletePenaltyBonus')->name('api.deletePenaltyBonus');





//********************AJAX*********************** */

Auth::routes();

Route::middleware(['check-permission'])->group(function () {

    // Admin_Panel --Start--
    Route::get('/admin_privilage','AdminController@admin_privilage');
    Route::get('/admin_privilage_show/{id}','AdminController@admin_privilage_show');
    Route::Post('/admin_privilage_edit/{id}','AdminController@admin_privilage_edit');
    // Admin_Panel --Stop--

    // Work_hours --Start--
    Route::get('/accept_hour','WorkHoursController@acceptHour');
    Route::get('/add_hour','WorkHoursController@addHour');
    Route::get('/view_hour','WorkHoursController@viewHourGet');
    Route::Post('/view_hour','WorkHoursController@viewHourPost');

    Route::get('/accept_hour_cadre','WorkHoursController@acceptHourCadre');

    Route::get('/view_hour_cadre','WorkHoursController@viewHourGetCadre');
    Route::Post('/view_hour_cadre','WorkHoursController@viewHourPostCadre');

    Route::get('/check_list_cadre','WorkHoursController@checkListCadre');
    // Work_hours --end--

    // Users --Start--
    Route::get('/add_consultant','UsersController@add_consultantGet');
    Route::POST('/add_consultant','UsersController@add_userPOST');

    Route::get('/edit_consultant/{id}','UsersController@edit_consultantGet');
    Route::POST('/edit_consultant/{id}','UsersController@edit_consultantPOST');

    Route::get('/edit_cadre/{id}','UsersController@edit_cadreGet');
    Route::POST('/edit_carde/{id}','UsersController@edit_cadrePOST');

    Route::get('/employee_management','UsersController@employee_managementGet');

    Route::get('/cadre_management','UsersController@cadre_managementGet');
    Route::get('/add_cadre','UsersController@add_cadreGet');
    Route::POST('/add_cadre','UsersController@add_userPOST');
    // Users -- STOP--


    // DKJ --START--
    Route::get('/dkjRaport','DkjController@dkjRaportGet');
    Route::POST('/dkjRaport','DkjController@dkjRaportPOST');

    Route::get('/dkjVerification','DkjController@dkjVerificationGet');

    Route::get('/jankyVerification','DkjController@jankyVerificationGet');
    Route::POST('/jankyVerification','DkjController@jankyVerificationPOST');

    Route::get('/jankyStatistics','DkjController@jankyStatistics');
    // DKJ -- STOP--

    // Schedule -- START --
    Route::get('/set_schedule','ScheduleController@setScheduleGet');
    Route::Post('/set_schedule','ScheduleController@setSchedulePOST');

    Route::get('/view_schedule','ScheduleController@viewScheduleGet');
    Route::Post('/view_schedule','ScheduleController@viewSchedulePOST');
    // schedule -- STOP --

    // Finances -- START --
    Route::get('/view_payment','FinancesController@viewPaymentGet');
    Route::Post('/view_payment','FinancesController@viewPaymentPOST');

    Route::get('/view_penalty_bonus','FinancesController@viewPenaltyBonusGet');
    Route::Post('/view_penalty_bonus','FinancesController@viewPenaltyBonusPOST');

    Route::get('/view_penalty_bonus_edit/{id}','FinancesController@viewPenaltyBonusGetEdit');//here
    Route::Post('/view_penalty_bonus_edit','FinancesController@viewPenaltyBonusPostEdit');//here

    Route::get('/view_summary_payment','FinancesController@viewSummaryPaymentGet');
    Route::Post('/view_summary_payment','FinancesController@viewSummaryPaymentPOST');
    // Finances -- STOP --

    // Equipment -- START --
    Route::get('/show_equipment','EquipmentsController@showEquipment');
    // Equipment -- STOP --


});
//////////////////////Testing ORM///////////////

  Route::get('/testorm', 'TestORM@test');
