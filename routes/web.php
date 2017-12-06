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
Route::POST('/uniquerEmail','UsersController@uniqueEmail')->name('api.uniqueEmail');
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

Route::POST('/getUserDepartmentInfo','DkjController@getUserDepartmentInfo')->name('api.getUserDepartmentInfo');


Route::POST('/get_stats','DkjController@getStats')->name('api.getStats');
Route::POST('/get_stats_dkj','DkjController@getStatsDkjMaster')->name('api.getStatsDkj');
Route::POST('/get_users','DkjController@getUsers')->name('api.getUsers');


Route::POST('/change_department','HomeController@changeDepartment')->name('api.changeDepartment');

Route::POST('/it_support','HomeController@itSupport')->name('api.itSupport');

//locker
Route::POST('/locker','AdminController@lockerPost')->name('api.locker');



//********************AJAX*********************** */

//Emails

Route::get('/hourReportTelemarketing', 'StatisticsController@MailhourReportTelemarketing'); // ok
Route::get('/weekReportTelemarketing', 'StatisticsController@MailweekReportTelemarketing'); // ok
Route::get('/monthReportTelemarketing', 'StatisticsController@MailmonthReportTelemarketing'); // ok
Route::get('/weekReportJanky', 'StatisticsController@weekReportJanky');
Route::get('/dayReportMissedRepo', 'StatisticsController@dayReportMissedRepo');

Route::get('/hourReportDkj', 'StatisticsController@MailhourReportDkj'); // ok
Route::get('/dayReportDkj', 'StatisticsController@dayReportDkj');
Route::get('/weekReportDkj', 'StatisticsController@weekReportDkj');

//End emails


Auth::routes();



Route::middleware(['check-permission','check-firewall'])->group(function () {
    Route::get('/', 'HomeController@index');
    // Admin_Panel --Start--
    Route::get('/admin_privilage','AdminController@admin_privilage');
    Route::get('/admin_privilage_show/{id}','AdminController@admin_privilage_show');
    Route::Post('/admin_privilage_edit/{id}','AdminController@admin_privilage_edit');
    Route::get('/locker','AdminController@lockerGet');
    Route::get('/add_department','AdminController@addDepartmentGet');
    Route::Post('/add_department','AdminController@addDepartmentPost');
    // Admin_Panel --Stop--

    // Password change --START--

    Route::get('/password_change', 'UsersController@passwordChangeGet');
    Route::Post('/password_change', 'UsersController@passwordChangePost');

    // Password change --STOP--
    // Work_hours --Start--
    Route::get('/accept_hour','WorkHoursController@acceptHour');
    Route::get('/add_hour','WorkHoursController@addHour');
    Route::get('/view_hour','WorkHoursController@viewHourGet');
    Route::Post('/view_hour','WorkHoursController@viewHourPost');

    Route::get('/accept_hour_cadre','WorkHoursController@acceptHourCadre');

    Route::get('/view_hour_cadre','WorkHoursController@viewHourGetCadre');
    Route::Post('/view_hour_cadre','WorkHoursController@viewHourPostCadre');

    Route::get('/check_list_cadre','WorkHoursController@checkListCadre');

    Route::get('/users_live','WorkHoursController@usersLive');
    // Work_hours --end--

    // Users --Start--
    Route::get('/add_consultant','UsersController@add_consultantGet');
    Route::POST('/add_consultant','UsersController@add_userPOST');

    Route::get('/edit_consultant/{id}','UsersController@edit_consultantGet');
    Route::POST('/edit_consultant/{id}','UsersController@edit_consultantPOST');

    Route::get('/edit_cadre/{id}','UsersController@edit_cadreGet');
    Route::POST('/edit_cadre/{id}','UsersController@edit_cadrePOST');

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

    Route::get('/departmentStatistics','DkjController@departmentStatisticsGet');
    Route::POST('/departmentStatistics','DkjController@departmentStatisticsPOST');

    Route::get('/departmentsStatistics','DkjController@departmentsStatisticsGet');
    Route::POST('/departmentsStatistics','DkjController@departmentsStatisticsPOST');

    Route::get('/consultantStatistics','DkjController@consultantStatisticsGet');
    Route::POST('/consultantStatistics','DkjController@consultantStatisticsPOST');

    Route::get('/showDkjEmployee','DkjController@showDkjEmployeeGet');
    Route::POST('/showDkjEmployee','DkjController@showDkjEmployeePOST');
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

    Route::get('/view_payment_cadre','FinancesController@viewPaymentCadreGet');
    Route::Post('/view_payment_cadre','FinancesController@viewPaymentCadrePOST');


    Route::get('/view_penalty_bonus','FinancesController@viewPenaltyBonusGet');
    Route::Post('/view_penalty_bonus','FinancesController@viewPenaltyBonusPOST');

    Route::Post('/create_penalty_bonus','FinancesController@createPenaltyBonusPOST');

    Route::get('/view_penalty_bonus_edit/{id}','FinancesController@viewPenaltyBonusGetEdit');
    Route::Post('/view_penalty_bonus_edit','FinancesController@viewPenaltyBonusPostEdit');

    Route::get('/view_summary_payment','FinancesController@viewSummaryPaymentGet');
    Route::Post('/view_summary_payment','FinancesController@viewSummaryPaymentPOST');

    // Finances -- STOP --

    // Equipment -- START --
    Route::get('/show_equipment','EquipmentsController@showEquipment');

    Route::get('/edit_equipment/{id}','EquipmentsController@editEquipmentGet');
    Route::Post('/edit_equipment/{id}','EquipmentsController@editEquipmentPost');

    Route::get('/add_equipment/{type}','EquipmentsController@addEquipmentGet');
    Route::Post('/add_equipment','EquipmentsController@addEquipmentPost');
    // Equipment -- STOP --

    //Notification Start
    Route::get('/add_notification', 'NotificationController@addNotificationGet');
    Route::Post('/add_notification', 'NotificationController@addNotificationPost');

    Route::get('/show_notification/{id}', 'NotificationController@showNotificationGet');
    Route::Post('/show_notification/{id}', 'NotificationController@showNotificationPost');

    Route::Post('add_comment_notifications/{id}', 'NotificationController@addCommentNotificationPost');

    Route::get('/show_all_notifications/{type}', 'NotificationController@showAllNotificationsGet');

    //Notification STOP


    //Statistics Start
    Route::get('/hour_report', 'StatisticsController@hourReportGet');
    Route::Post('/hour_report', 'StatisticsController@hourReportPost');

    Route::Post('/hour_report_edit', 'StatisticsController@hourReportEditPost');
    //Statistics Stop

    //Report Page Start
    Route::get('/pageHourReportTelemarketing', 'StatisticsController@pageHourReportTelemarketing');
    Route::get('/pageWeekReportTelemarketing', 'StatisticsController@pageWeekReportTelemarketing');
    Route::get('/pageMonthReportTelemarketing', 'StatisticsController@pageMonthReportTelemarketing');


    Route::get('/pageHourReportDKJ', 'StatisticsController@pageHourReportDKJ');
    Route::get('/pageWeekReportDKJ', 'ReportPageController@pageWeekReportDKJ');
    Route::get('/pageMonthReportDKJ', 'ReportPageController@pageMonthReportDKJ');

    Route::get('/pageWeekReportJanky', 'ReportPageController@pageWeekReportJanky');

    //Report Page STOP
});
//////////////////////Testing ORM///////////////

  Route::get('/testorm', 'TestORM@test');
