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
Route::POST('/datatableCadreManagementFire','UsersController@datatableCadreManagementFire')->name('api.datatableCadreManagementFire');


Route::POST('/datatableDkjRaport','DkjController@datatableDkjRaport')->name('api.datatableDkjRaport');
Route::POST('/getUser','DkjController@getUser')->name('api.getUser');
Route::POST('/dkjRaportSave','DkjController@dkjRaportSave')->name('api.dkjRaportSave');

Route::POST('/datatableDkjVerification','DkjController@datatableDkjVerification')->name('api.datatableDkjVerification');
Route::POST('/saveDkjVerification','DkjController@saveDkjVerification')->name('api.saveDkjVerification');
Route::POST('/datatableShowDkjVerification','DkjController@datatableShowDkjVerification')->name('api.datatableShowDkjVerification');
Route::POST('/datatableDkjShowEmployee','DkjController@datatableDkjShowEmployee')->name('api.datatableDkjShowEmployee');

Route::POST('/datatableCadreHR','UsersController@datatableCadreHR')->name('api.datatableCadreHR');
Route::POST('/datatableMyNotifications','NotificationController@datatableMyNotifications')->name('api.datatableMyNotifications');

/* Equipment start */
Route::POST('/datatableShowLaptop','EquipmentsController@datatableShowLaptop')->name('api.datatableShowLaptop');
Route::POST('/datatableShowTablet','EquipmentsController@datatableShowTablet')->name('api.datatableShowTablet');
Route::POST('/datatableShowPhone','EquipmentsController@datatableShowPhone')->name('api.datatableShowPhone');
Route::POST('/datatableShowSimCard','EquipmentsController@datatableShowSimCard')->name('api.datatableShowSimCard');
Route::POST('/datatableShowMonitor','EquipmentsController@datatableShowMonitor')->name('api.datatableShowMonitor');
Route::POST('/datatableShowPrinter','EquipmentsController@datatableShowPrinter')->name('api.datatableShowPrinter');
/* Equipment stop */

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
Route::POST('/count_notifications','HomeController@itCountNotifications')->name('api.itCountNotifications');
Route::POST('/datatableShowNewNotifications','NotificationController@datatableShowNewNotifications')->name('api.datatableShowNewNotifications'); //tu zmienic z ORM
Route::POST('/datatableShowInProgressNotifications','NotificationController@datatableShowInProgressNotifications')->name('api.datatableShowInProgressNotifications'); //tu zmienic z ORM
Route::POST('/datatableShowFinishedNotifications','NotificationController@datatableShowFinishedNotifications')->name('api.datatableShowFinishedNotifications'); //tu zmienic z ORM

//locker / Multiple departments
Route::POST('/locker','AdminController@lockerPost')->name('api.locker');

//firewall delete users
Route::POST('/firewallDeleteUser','AdminController@firewallDeleteUser')->name('api.firewallDeleteUser');

//notifications moving
Route::POST('/getNotficationsJanky','NotificationController@getNotficationsJanky')->name('api.getNotficationsJanky');

/* TEST AJAX ROUTES START */

Route::POST('/addTestQuestion','TestsController@addTestQuestion')->name('api.addTestQuestion');
Route::POST('/saveCategoryName','TestsController@saveCategoryName')->name('api.saveCategoryName');
Route::POST('/categoryStatusChange','TestsController@categoryStatusChange')->name('api.categoryStatusChange');
Route::POST('/showCategoryQuestions','TestsController@showCategoryQuestions')->name('api.showCategoryQuestions');

Route::POST('/editTestQuestion','TestsController@editTestQuestion')->name('api.editTestQuestion');
Route::POST('/deleteTestQuestion','TestsController@deleteTestQuestion')->name('api.deleteTestQuestion');
Route::POST('/mainTableCounter','TestsController@mainTableCounter')->name('api.mainTableCounter');

Route::POST('/showQuestionDatatable','TestsController@showQuestionDatatable')->name('api.showQuestionDatatable');
Route::POST('/saveTestWithUser','TestsController@saveTestWithUser')->name('api.saveTestWithUser');
Route::POST('/editTestWithUser','TestsController@editTestWithUser')->name('api.editTestWithUser');

Route::POST('/datatableShowCheckedTests','TestsController@datatableShowCheckedTests')->name('api.datatableShowCheckedTests');
Route::POST('/datatableShowUncheckedTests','TestsController@datatableShowUncheckedTests')->name('api.datatableShowUncheckedTests');

Route::POST('/activateTest','TestsController@activateTest')->name('api.activateTest');

Route::POST('/deactivateTest','TestsController@deactivateTest')->name('api.deactivateTest');

Route::POST('/testAttempt','TestsController@testAttempt')->name('api.testAttempt');

Route::POST('/getRepeatQuestion','TestsController@getRepeatQuestion')->name('api.getRepeatQuestion'); //tu zmienic z ORM
Route::POST('/saveTestTemplate','TestsController@saveTestTemplate')->name('api.saveTestTemplate'); //tu zmienic z ORM

Route::POST('/getTemplateQuestion','TestsController@getTemplateQuestion')->name('api.getTemplateQuestion'); //tu zmienic z ORM

Route::POST('/saveEditTemplate','TestsController@saveEditTemplate')->name('api.saveEditTemplate');

Route::POST('/deleteTester','TestsController@deleteTester')->name('api.deleteTester');

Route::POST('/datatableAllTests','AdminController@datatableAllTests')->name('api.datatableAllTests');

/* TEST AJAX ROUTES STOP */

//** RECRUITMENT AJAX */

Route::POST('/getCandidateForGrpupTrainingInfo','GroupTrainingController@getCandidateForGrpupTrainingInfo')->name('api.getCandidateForGrpupTrainingInfo');
Route::POST('/datatableTrainingGroupList','GroupTrainingController@datatableTrainingGroupList')->name('api.datatableTrainingGroupList');
Route::POST('/getGrpupTrainingInfo','GroupTrainingController@getGrpupTrainingInfo')->name('api.getGrpupTrainingInfo');
Route::POST('/getAttemptLevel', 'RecruitmentAttemptController@getAttemptLevel')->name('api.getAttemptLevel');
Route::POST('/addAttemptLevel', 'RecruitmentAttemptController@addAttemptLevel')->name('api.addAttemptLevel');
Route::POST('/editAttemptLevel', 'RecruitmentAttemptController@editAttemptLevel')->name('api.editAttemptLevel');
Route::POST('/deleteAttemptLevel', 'RecruitmentAttemptController@deleteAttemptLevel')->name('api.deleteAttemptLevel');

Route::POST('/getCandidateSource', 'RecruitmentAttemptController@getCandidateSource')->name('api.getCandidateSource');
Route::POST('/addCandidateSource', 'RecruitmentAttemptController@addCandidateSource')->name('api.addCandidateSource');
Route::POST('/editCandidateSource', 'RecruitmentAttemptController@editCandidateSource')->name('api.editCandidateSource');
Route::POST('/deleteCandidateSource', 'RecruitmentAttemptController@deleteCandidateSource')->name('api.deleteCandidateSource');

Route::POST('/addNewCandidate', 'RecruitmentAttemptController@addNewCandidate')->name('api.addNewCandidate');
Route::POST('/editCandidate', 'RecruitmentAttemptController@editCandidate')->name('api.editCandidate');

Route::POST('/startNewRecruitment', 'RecruitmentAttemptController@startNewRecruitment')->name('api.startNewRecruitment');
Route::POST('/stopRecruitment', 'RecruitmentAttemptController@stopRecruitment')->name('api.stopRecruitment');
Route::POST('/addRecruitmentLevel', 'RecruitmentAttemptController@addRecruitmentLevel')->name('api.addRecruitmentLevel');
Route::POST('/addToTraining', 'RecruitmentAttemptController@addToTraining')->name('api.addToTraining');

Route::POST('/uniqueCandidatePhone', 'RecruitmentAttemptController@uniqueCandidatePhone')->name('api.uniqueCandidatePhone');

Route::POST('/datatableShowCandidates', 'CandidateController@datatableShowCandidates')->name('api.datatableShowCandidates');

/** */

//********************AJAX*********************** */

//Emails

Route::get('/hourReportTelemarketing', 'StatisticsController@MailhourReportTelemarketing'); // ok
Route::get('/weekReportTelemarketing', 'StatisticsController@MailweekReportTelemarketing'); // ok
Route::get('/monthReportTelemarketing', 'StatisticsController@MailmonthReportTelemarketing'); // ok
Route::get('/dayReportTelemarketing', 'StatisticsController@MailDayReportTelemarketing'); // tutaj dodac

Route::get('/weekReportJanky', 'StatisticsController@MailweekReportJanky');
Route::get('/dayReportMissedRepo', 'StatisticsController@dayReportMissedRepo');

Route::get('/hourReportDkj', 'StatisticsController@MailhourReportDkj'); // ok
Route::get('/dayReportDkj', 'StatisticsController@dayReportDkj');// ok
Route::get('/weekReportDkj', 'StatisticsController@MailWeekReportDkj');// ok
Route::get('/monthReportDkj', 'StatisticsController@monthReportDkj');// ok

Route::get('/dayReportEmployeeDkj', 'StatisticsController@MaildayReportEmployeeDkj');
Route::get('/hourReportDkjEmployee', 'StatisticsController@MailHourReportDkjEmployee');
Route::get('/weekReportEmployeeDkj', 'StatisticsController@MailweekReportEmployeeDkj');

Route::get('/hourReportChecked', 'StatisticsController@hourReportChecked');
Route::get('/dayReportChecked', 'StatisticsController@dayReportChecked');
Route::get('/weekReportChecked', 'StatisticsController@weekReportChecked');


// maile dotyczące bazy danych (telefonów) maile

Route::get('/weekReportNewBaseWeek', 'DatabaseURLController@MailWeekRaportNewBaseWeek');
Route::get('/monthReportNewBaseWeek', 'DatabaseURLController@MailMonthRaportNewBaseWeek');

Route::get('/dayReportDatabaseUse', 'DatabaseURLController@MailDayRaportDatabaseUse');
Route::get('/weekReportDatabaseUse', 'DatabaseURLController@MailWeekRaportDatabaseUse');
Route::get('/monthReportDatabaseUse', 'DatabaseURLController@MailMonthRaportDatabaseUse');
//End emails





Auth::routes();
//'check-firewall'
Route::middleware(['check-permission', 'check-firewall'])->group(function () {
    Route::get('/', 'HomeController@index');
    // Admin_Panel --Start--
    Route::get('/admin_privilage','AdminController@admin_privilage');

    Route::get('/admin_privilage_show/{id}','AdminController@admin_privilage_show');
    Route::Post('/admin_privilage_edit/{id}','AdminController@admin_privilage_edit');

    Route::get('/locker','AdminController@lockerGet');

    Route::get('/add_department','AdminController@addDepartmentGet');
    Route::Post('/add_department','AdminController@addDepartmentPost');

    Route::get('/edit_department','AdminController@editDepartmentGet');
    Route::Post('/edit_department','AdminController@editDepartmentPost');

    Route::get('/set_multiple_department','AdminController@multipleDepartmentGet');
    Route::Post('/set_multiple_department','AdminController@multipleDepartmentPost');

    Route::get('/create_link','AdminController@createLinkGet');
    Route::Post('/create_link','AdminController@createLinkPost');

    Route::get('/firewall_ip', 'AdminController@firewallGet');
    Route::POST('/firewall_ip', 'AdminController@firewallPost');

    Route::get('/firewall_privileges', 'AdminController@firewallPrivilegesGet');
    Route::POST('/firewall_privileges', 'AdminController@firewallPrivilegesPost');

    Route::get('/check_all_tests', 'AdminController@check_all_tests');

    Route::get('/show_test_for_admin/{id}', 'AdminController@show_test_for_admin');
    // Admin_Panel --Stop--

    // Password change --START--

    Route::get('/password_change', 'UsersController@passwordChangeGet')->name('changePassword');
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
    //Route::POST('/edit_consultant/{id}','UsersController@edit_consultantPOST');

    Route::get('/edit_cadre/{id}','UsersController@edit_cadreGet');
    Route::POST('/edit_cadre/{id}','UsersController@edit_cadrePOST');

    Route::get('/employee_management','UsersController@employee_managementGet');

    Route::get('/cadre_management','UsersController@cadre_managementGet');
    Route::get('/cadre_management_fire','UsersController@cadre_management_fireGet');

    Route::get('/add_cadre','UsersController@add_cadreGet');
    Route::POST('/add_cadre','UsersController@add_userPOST');

    Route::get('/cadre_hr', 'UsersController@cadreHRGet');
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


    Route::get('/timesheet', 'ScheduleController@timesheetGet');
    Route::POST('/timesheet', 'ScheduleController@timesheetPost');

    Route::get('/timesheet_cadre', 'ScheduleController@timesheetCadreGet');
    Route::POST('/timesheet_cadre', 'ScheduleController@timesheetCadrePost');
    // schedule -- STOP --

    // Finances -- START --
    Route::get('/view_payment','FinancesController@viewPaymentGet');
    Route::Post('/view_payment','FinancesController@viewPaymentPOST');

    Route::get('/view_payment_cadre','FinancesController@viewPaymentCadreGet');
    Route::Post('/view_payment_cadre','FinancesController@viewPaymentCadrePOST');


    Route::get('/view_penalty_bonus','FinancesController@viewPenaltyBonusGet');
    Route::Post('/view_penalty_bonus','FinancesController@viewPenaltyBonusPOST');

    Route::Post('/create_penalty_bonus','FinancesController@createPenaltyBonusPOST');

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

    Route::get('/show_all_notifications', 'NotificationController@showAllNotificationsGet');

    Route::get('/my_notifications','NotificationController@myNotifications');

    Route::get('/judge_notification/{id}','NotificationController@judgeNotificationGet');
    Route::Post('/judge_notification','NotificationController@judgeNotificationPost');

    Route::get('/it_cadre','NotificationController@ITCadreGet');

    Route::get('/it_worker/{id}', 'NotificationController@ITWorkerGet');

    Route::get('/view_notification/{id}', 'NotificationController@viewNotification');
    //Notification STOP


    //Statistics Start
    Route::get('/hour_report', 'StatisticsController@hourReportGet');
    Route::Post('/hour_report', 'StatisticsController@hourReportPost');

    Route::Post('/hour_report_edit', 'StatisticsController@hourReportEditPost');
    //Statistics Stop

    //Report Page Start
    Route::get('/pageDayReportMissedRepo', 'StatisticsController@dayReportMissedRepo');


    Route::get('/pageHourReportTelemarketing', 'StatisticsController@pageHourReportTelemarketing');
    Route::get('/pageWeekReportTelemarketing', 'StatisticsController@pageWeekReportTelemarketing');
    Route::get('/pageMonthReportTelemarketing', 'StatisticsController@pageMonthReportTelemarketing');
    Route::get('/pageDayReportTelemarketing', 'StatisticsController@pageDayReportTelemarketing');


    Route::get('/pageHourReportDKJ', 'StatisticsController@pageHourReportDKJ');
    Route::get('/pageDayReportDKJ', 'StatisticsController@pageDayReportDKJ');
    Route::get('/pageWeekReportDKJ', 'StatisticsController@pageWeekReportDKJ');
    Route::get('/pageMonthReportDKJ', 'StatisticsController@pageMonthReportDKJ');

    Route::get('/pageDayReportEmployeeDkj', 'StatisticsController@pageDayReportEmployeeDkj');
    Route::get('/pageWeekReportEmployeeDkj', 'StatisticsController@pageWeekReportEmployeeDkj');

    Route::get('/pageHourReportChecked', 'StatisticsController@pageHourReportChecked');
    Route::get('/pageDayReportChecked', 'StatisticsController@pageDayReportChecked');
    Route::get('/pageWeekReportChecked', 'StatisticsController@pageWeekReportChecked');

    Route::get('/pageWeekReportJanky', 'StatisticsController@pageWeekReportJanky');

    Route::get('/pageHourReportDkjEmployee', 'StatisticsController@pageHourReportDkjEmployee');

    Route::get('/pageWeekReportNewBaseWeek', 'DatabaseURLController@pageWeekRaportNewBaseWeek');
    Route::get('/pageMonthReportNewBaseWeek', 'DatabaseURLController@pageMonthRaportNewBaseWeek');

    Route::get('/pageDayReportDatabaseUse', 'DatabaseURLController@pageDayRaportDatabaseUse');
    Route::get('/pageWeekReportDatabaseUse', 'DatabaseURLController@pageWeekRaportDatabaseUse');
    Route::get('/pageMonthReportDatabaseUse', 'DatabaseURLController@pageMonthRaportDatabaseUse');

    //Report Page STOP

    //TESTS START //

    Route::get('/tests_admin_panel', 'TestsController@testsAdminPanelGet');
    Route::POST('/tests_admin_panel', 'TestsController@testsAdminPanelPost');

    Route::get('/tester_list', 'TestsController@testerListGet');
    Route::POST('/tester_list', 'TestsController@testerListPost');

    Route::get('/test_user/{id}', 'TestsController@testUserGet'); // tutaj bedzie {id}
    Route::POST('/test_user', 'TestsController@testUserPost');

    Route::get('/all_user_tests', 'TestsController@allUserTests');

    Route::get('/add_test', 'TestsController@addTestGet');
    Route::POST('/add_test', 'TestsController@addTestPost');
    Route::get('/view_test/{id}', 'TestsController@viewTest'); // podgląd testu
    Route::get('/delete_test/{id}', 'TestsController@deleteTest'); // usunięcie  testu
    Route::get('/show_tests', 'TestsController@showTestsGet');
    Route::POST('/show_tests', 'TestsController@showTestsPost');

    Route::get('/check_test/{id}', 'TestsController@testCheckGet'); // tutaj bedzie {id}
    Route::POST('/check_test', 'TestsController@testCheckPost');

    Route::get('/tests_statistics', 'TestsController@testsStatisticsGet');
    Route::POST('/tests_statistics', 'TestsController@testsStatisticsPost');

    Route::get('/one_test_statistics', 'TestsController@testStatisticsGet');
    Route::POST('/one_test_statistics', 'TestsController@testStatisticsPost');

    Route::get('/employee_statistics/{id}', 'TestsController@employeeTestsStatisticsGet'); // tutaj bedzie {id}

    Route::get('/department_statistics', 'TestsController@departmentTestsStatisticsGet');
    Route::POST('/department_statistics', 'TestsController@departmentTestsStatisticsPost');

    Route::get('/test_result/{id}', 'TestsController@testResult');

    Route::get('/add_test_template', 'TestsController@addTestTemplate'); // szablony testów
    Route::get('/showTestTemplate', 'TestsController@showTestTemplate'); //wyświetlenie szablonów
    Route::get('/deleteTemplate/{id}', 'TestsController@deleteTemplate'); //usunięcie szablonów
    Route::get('/viewTestTemplate/{id}', 'TestsController@viewTestTemplate'); //edycja szablonów


    Route::get('/all_tests', 'TestsController@allTestsGet');
    //TESTS STOP//


    /**REKRUTACJA START */
    Route::get('add_group_training', 'GroupTrainingController@add_group_training');
    Route::get('/add_candidate', 'RecruitmentAttemptController@add_candidate');
    Route::get('/candidateProfile/{id}', 'RecruitmentAttemptController@candidateProfile');

    Route::get('/recruitment_resources', 'RecruitmentAttemptController@recruitment_resources');

    Route::get('/all_candidates', 'CandidateController@all_candidates');

    /**REKRUTACJA STOP */
});
//////////////////////Testing ORM///////////////

  Route::get('/testorm', 'TestORM@test');
