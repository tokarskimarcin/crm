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
Route::POST('/uniquerEmailEdit','UsersController@uniquerEmailEdit')->name('api.uniquerEmailEdit');
Route::POST('/uniquePBX','UsersController@uniquePBX')->name('api.uniquePBX');
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

Route::POST('/deleteMedicalPackage','UsersController@deleteMedicalPackage')->name('api.deleteMedicalPackage');

Route::POST('/editInterviewDateTime','CandidateController@editInterviewDateTime')->name('api.editInterviewDateTime');

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

Route::POST('/paymentStory','FinancesController@paymentStory')->name('api.paymentStory');

Route::POST('/saveCoaching','CoachingController@saveCoaching')->name('api.saveCoaching');
Route::POST('/deleteCoaching','CoachingController@deleteCoaching')->name('api.deleteCoaching');
Route::POST('/getCoaching','CoachingController@getCoaching')->name('api.getCoaching');
Route::POST('/getcoach_list','CoachingController@getcoach_list')->name('api.getcoach_list');

/* TEST AJAX ROUTES STOP */

//** RECRUITMENT AJAX */


Route::POST('/deleteGroupTraining', 'GroupTrainingController@deleteGroupTraining')->name('api.deleteGroupTraining');
Route::POST('/saveGroupTraining', 'GroupTrainingController@saveGroupTraining')->name('api.saveGroupTraining');
Route::POST('/EndGroupTraining', 'GroupTrainingController@EndGroupTraining')->name('api.EndGroupTraining');
Route::POST('/EndGroupTrainingForCandidate', 'GroupTrainingController@EndGroupTrainingForCandidate')->name('api.EndGroupTrainingForCandidate');

Route::post('editTrainingDate', 'GroupTrainingController@editTrainingDate')->name('api.editTrainingDate');

Route::POST('/getCandidateForGroupTrainingInfo','GroupTrainingController@getCandidateForGroupTrainingInfo')->name('api.getCandidateForGroupTrainingInfo');
Route::POST('/datatableTrainingGroupList','GroupTrainingController@datatableTrainingGroupList')->name('api.datatableTrainingGroupList');
Route::POST('/getGroupTrainingInfo','GroupTrainingController@getGroupTrainingInfo')->name('api.getGroupTrainingInfo');

Route::POST('/datatableRecruitmentStatisticsLeader','RecruitmentAttemptController@datatableRecruitmentStatisticsLeader')->name('api.datatableRecruitmentStatisticsLeader');


Route::POST('/getCandidateSource', 'RecruitmentAttemptController@getCandidateSource')->name('api.getCandidateSource');
Route::POST('/addCandidateSource', 'RecruitmentAttemptController@addCandidateSource')->name('api.addCandidateSource');
Route::POST('/editCandidateSource', 'RecruitmentAttemptController@editCandidateSource')->name('api.editCandidateSource');
Route::POST('/deleteCandidateSource', 'RecruitmentAttemptController@deleteCandidateSource')->name('api.deleteCandidateSource');

Route::POST('/addNewCandidate', 'CandidateController@addNewCandidate')->name('api.addNewCandidate');
Route::POST('/editCandidate', 'CandidateController@editCandidate')->name('api.editCandidate');
Route::POST('/addInterviewDate', 'RecruitmentAttemptController@addInterviewDate')->name('api.addInterviewDate');

Route::POST('/startNewRecruitment', 'CandidateController@startNewRecruitment')->name('api.startNewRecruitment');
Route::POST('/stopRecruitment', 'CandidateController@stopRecruitment')->name('api.stopRecruitment');
Route::POST('/addRecruitmentLevel', 'CandidateController@addRecruitmentLevel')->name('api.addRecruitmentLevel');
Route::POST('/addToTraining', 'CandidateController@addToTraining')->name('api.addToTraining');

Route::POST('/addConsultantToSession', 'CandidateController@addConsultantToSession')->name('api.addConsultantToSession');

Route::POST('/uniqueCandidatePhone', 'CandidateController@uniqueCandidatePhone')->name('api.uniqueCandidatePhone');

Route::POST('/datatableShowCandidates', 'CandidateController@datatableShowCandidates')->name('api.datatableShowCandidates');
Route::POST('/datatableShowCadreCandidates', 'CandidateController@datatableShowCadreCandidates')->name('api.datatableShowCadreCandidates');

Route::POST('/myInterviews', 'RecruitmentAttemptController@myInterviews')->name('api.myInterviews');

Route::POST('/getStatusResults', 'RecruitmentAttemptController@getStatusResults')->name('api.getStatusResults');
Route::POST('/statusResultChange', 'RecruitmentAttemptController@statusResultChange')->name('api.statusResultChange');

Route::POST('/recruiterData', 'RecruitmentAttemptController@recruiterData')->name('api.recruiterData');
Route::POST('/trainerData', 'RecruitmentAttemptController@trainerData')->name('api.trainerData');
Route::POST('/recruiterTrainingsData', 'RecruitmentAttemptController@recruiterTrainingsData')->name('api.recruiterTrainingsData');
Route::POST('/datatableTrainingData', 'RecruitmentStoryController@datatableTrainingData')->name('api.datatableTrainingData');

Route::POST('/delete_notification', 'NotificationController@delete_notification')->name('api.delete_notification');

Route::POST('/getMedicalPackagesAdminData', 'AdminController@getMedicalPackagesAdminData')->name('api.getMedicalPackagesAdminData');
Route::POST('/getMedicalPackageData', 'AdminController@getMedicalPackageData')->name('api.getMedicalPackageData');
Route::POST('/saveMedicalPackageData', 'AdminController@saveMedicalPackageData')->name('api.saveMedicalPackageData');


Route::POST('/getDaysInMonth', 'StatisticsController@getDaysInMonth')->name('api.getDaysInMonth');


Route::POST('/datatableCoachingTable','CoachingController@datatableCoachingTable')->name('api.datatableCoachingTable');
Route::POST('/acceptCoaching', 'CoachingController@acceptCoaching')->name('api.acceptCoaching');;
//Bootstrap notifications //

Route::post('getBootstrapNotifications', 'HomeController@getBootstrapNotifications')->name('getBootstrapNotifications');

//bootstrap notifications end //


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

Route::get('/hourReportTimeOnRecord', 'StatisticsController@MailhourReportTimeOnRecord');

// maile dotyczące bazy danych (telefonów) maile

Route::get('/weekReportNewBaseWeek', 'DatabaseURLController@MailWeekRaportNewBaseWeek');
Route::get('/monthReportNewBaseWeek', 'DatabaseURLController@MailMonthRaportNewBaseWeek');

Route::get('/dayReportDatabaseUse', 'DatabaseURLController@MailDayRaportDatabaseUse');
Route::get('/weekReportDatabaseUse', 'DatabaseURLController@MailWeekRaportDatabaseUse');
Route::get('/monthReportDatabaseUse', 'DatabaseURLController@MailMonthRaportDatabaseUse');
//End emails

// maila dotyczące rekrutacji
Route::get('/dayReportRecruitmentFlow', 'StatisticsController@MaildayReportRecruitmentFlow');
Route::get('/dayReportRecruitmentTrainingGroup', 'StatisticsController@MaildayReportTrainingGroup');
Route::get('/dayReportInterviews', 'StatisticsController@MaildayReportInterviews');
Route::get('/dayReportHireCandidate', 'StatisticsController@MaildayReportHireCandidate');

Route::get('/weekReportRecruitmentFlow', 'StatisticsController@MailweekReportRecruitmentFlow');
Route::get('/weekReportTrainingGroup', 'StatisticsController@MailweekReportTrainingGroup');
Route::get('/weekReportInterviews', 'StatisticsController@MailweekReportInterviews');
Route::get('/weekReportHireCandidate', 'StatisticsController@MailweekReportHireCandidate');

Route::get('/monthReportRecruitmentFlow', 'StatisticsController@MailmonthReportRecruitmentFlow');
Route::get('/monthReportTrainingGroup', 'StatisticsController@MailmonthReportTrainingGroup');
Route::get('/monthReportInterviews', 'StatisticsController@MailmonthReportInterviews');
Route::get('/monthReportHireCandidate', 'StatisticsController@MailmonthReportHireCandidate');

//Emaile dotyczące statystyk oddziałów
Route::get('/monthReportSummaryDepartments', 'StatisticsController@MailMonthReportDepartments');

Route::get('/dayReportDepartments', 'StatisticsController@MailDayDepartmentsReport');
Route::get('/dayReportCoaches', 'StatisticsController@MailDayReportCoaches');
Route::get('/hourReportCoaches', 'StatisticsController@MailHourReportCoaches');

//Maila dotyczące wyłączonych kont
//Raport Usunietych kont
Route::get('/weekReportUnuserdAccount','StatisticsController@MailWeekReportUnuserdAccount');

//wyłączenie danych użytkowników którzy nie logowali się już 14 dni i więcej
Route::GET('/disableUnusedAccount', 'UsersController@DisableUnusedAccount');


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

    Route::get('/edit_medical_package', 'AdminController@edit_medical_package');
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

    Route::get('/medical_packages_all', 'UsersController@medicalPackagesAllGet');
    Route::POST('/medical_packages_all', 'UsersController@medicalPackagesAllPost');
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

    Route::get('/medicalPackagesRaportExtended','UsersController@medicalPackagesRaportExtendedGet');
    Route::post('/medicalPackagesRaportExtended','UsersController@medicalPackagesRaportExtendedPost');
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

    Route::get('/pageReportDepartments', 'StatisticsController@pageReportDepartmentsGet');
    Route::post('/pageReportDepartments', 'StatisticsController@pageReportDepartmentsPost');
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


    Route::get('/pageHourReportTimeOnRecord', 'StatisticsController@pageHourReportTimeOnRecord');

    Route::get('/pageMailMonthReportDepartments', 'StatisticsController@pageMailMonthReportDepartments');

    Route::get('/pageMonthReportCoach', 'StatisticsController@pageMonthReportCoachGet');
    Route::post('/pageMonthReportCoach', 'StatisticsController@pageMonthReportCoachPost');
    Route::get('/pageDayReportCoaches', 'StatisticsController@pageDayReportCoachGet');
    Route::post('/pageDayReportCoaches', 'StatisticsController@pageDayReportCoachPost');
    Route::get('/pageSummaryDayReportCoaches', 'StatisticsController@pageSummaryDayReportCoachesGet');
    Route::post('/pageSummaryDayReportCoaches', 'StatisticsController@pageSummaryDayReportCoachesPost');

    Route::get('/pageMonthReportDepartmentsSummary', 'StatisticsController@pageMonthReportDepartmentsSummaryGet');
    Route::post('/pageMonthReportDepartmentsSummary', 'StatisticsController@pageMonthReportDepartmentsSummaryPost');

    Route::get('/pageWeekReportDepartmentsSummary', 'StatisticsController@pageWeekReportDepartmentsSummaryGet');
    Route::post('/pageWeekReportDepartmentsSummary', 'StatisticsController@pageWeekReportDepartmentsSummaryPost');

    Route::get('/pageMonthReportCoachSummary', 'StatisticsController@pageMonthReportCoachSummaryGet');
    Route::post('/pageMonthReportCoachSummary', 'StatisticsController@pageMonthReportCoachSummaryPost');

    Route::get('/monthReportConsultant', 'StatisticsController@monthReportConsultantGet');
    Route::post('/monthReportConsultant', 'StatisticsController@monthReportConsultantPost');

    //Raporty Rekrutacji

    //Dzienny
    Route::get('/pageDayReportRecruitmentFlow','StatisticsController@pageDayReportRecruitmentFlow');
    Route::get('/pageDayReportRecruitmentTrainingGroup','StatisticsController@pageDayReportTrainingGroup');
    Route::get('/pageDayReportInterviews','StatisticsController@pageDayReportInterviews');
    Route::get('/pageDayReportHireCandidate','StatisticsController@pageDayReportHireCandidate');
    //Tygodniowy
    Route::get('/pageWeekReportRecruitmentFlow','StatisticsController@pageWeekReportRecruitmentFlow');
    Route::get('/pageWeekReportTrainingGroup','StatisticsController@pageWeekReportTrainingGroup');
    Route::get('/pageWeekReportInterviews','StatisticsController@pageWeekReportInterviews');
    Route::get('/pageWeekReportHireCandidate','StatisticsController@pageWeekReportHireCandidate');
    //Miesięczny
    Route::get('/pageMonthReportRecruitmentFlow','StatisticsController@pageMonthReportRecruitmentFlow');
    Route::get('/pageMonthReportTrainingGroup','StatisticsController@pageMonthReportTrainingGroup');
    Route::get('/pageMonthReportInterviews','StatisticsController@pageMonthReportInterviews');
    Route::get('/pageMonthReportHireCandidate','StatisticsController@pageMonthReportHireCandidate');


    //Raport Usunietych kont
    Route::get('/pageWeekReportUnuserdAccount','StatisticsController@pageWeekReportUnuserdAccount');
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

    Route::get('add_group_training_2', 'GroupTrainingController@add_group_training_2');


    Route::get('/add_candidate', 'CandidateController@add_candidate');
    Route::get('/candidateProfile/{id}', 'CandidateController@candidateProfile');

    Route::get('/recruitment_resources', 'RecruitmentAttemptController@recruitment_resources');

    Route::get('/all_candidates', 'CandidateController@all_candidates');

    Route::get('/interviews_all', 'RecruitmentAttemptController@interviewsAllGet');

    Route::get('/recruitment_admin', 'RecruitmentAttemptController@recruitment_admin');


    Route::get('/recruitment_statistics_leader', 'RecruitmentAttemptController@recruitment_statistics_leaderGET');

    Route::get('/pageReportInterviews', 'RecruitmentStoryController@pageReportInterviewsGet');
    Route::post('/pageReportInterviews', 'RecruitmentStoryController@pageReportInterviewsPost');

    Route::get('/pageReportNewAccount', 'RecruitmentStoryController@pageReportNewAccountGet');
    Route::post('/pageReportNewAccount', 'RecruitmentStoryController@pageReportNewAccountPost');

    Route::get('/pageReportTraining', 'RecruitmentStoryController@pageReportTrainingGet');

    Route::get('/pageReportRecruitmentFlow', 'RecruitmentStoryController@pageReportRecruitmentFlowGet');
    Route::post('/pageReportRecruitmentFlow', 'RecruitmentStoryController@pageReportRecruitmentFlowPost');


    /**REKRUTACJA STOP */


    /** Tabela Postępów Coaching */

    Route::get('/progress_table', 'CoachingController@progress_tableGET');
    Route::get('/progress_table_for_director', 'CoachingController@progress_table_for_directorGET');
    Route::get('/progress_table_for_manager', 'CoachingController@progress_table_for_managerGET');


    Route::get('/pageReportCoaching', 'StatisticsController@pageReportCoachingGet');
    Route::post('/pageReportCoaching', 'StatisticsController@pageReportCoachingPost');

    Route::get('/progress_table_manager', 'CoachingController@progress_table_managerGET');

});
//////////////////////Testing ORM///////////////
///
///
Route::get('/addAudit', 'AuditController@auditMethodGet');
Route::post('/add', 'AuditController@ajax')->name('api.ajax');

Route::post('/addAudit', 'AuditController@auditMethodPost');

Route::post('/handleForm', 'AuditController@handleFormPost');

Route::get('/showAudits', 'AuditController@showAuditsGet');
Route::post('/showAudits', 'AuditController@showAuditsPost')->name('api.auditTable');

Route::get('/audit/{id}', 'AuditController@editAuditGet');
Route::post('/handleEdit', 'AuditController@editAuditPost');

Route::get('/editAuditTemplates', 'AdminController@editAuditTemplatesGet');
Route::post('/addTemplate', 'AdminController@addTemplatePost');
Route::get('/editAudit/{id}', 'AdminController@editAuditGet');
Route::post('/editAudit', 'AdminController@editAuditPost')->name('api.editAudit');
Route::post('/editAuditPage', 'AdminController@editDatabasePost');

//LINK GROUP
Route::Post('/addGroup', 'AdminController@addGroup');
Route::Post('/removeGroup', 'AdminController@removeGroup');
//END LINK GROUP
//CHARTS
Route::get('/charts', 'ScreensController@showScreensGet');
//ENDCHARTS
//COACHINGS
Route::get('/pageReportCoachingWeekSummary', 'StatisticsController@pageReportCoachingSummaryGet');
Route::post('/pageReportCoachingWeekSummary', 'StatisticsController@pageReportCoachingSummaryPost');
Route::get('/ReportCoachingSummary', 'StatisticsController@MailReportCoachingSummary');

Route::get('/ReportCoaching', 'StatisticsController@MailpageReportCoaching');
//END COACHINGS
Route::get('/dept/{id}','ScreensController@monitorMethod');
Route::get('/screen_table','ScreensController@screenMethod');
//pobieranie danych po oddziałach godzinny
  Route::get('/testorm', 'TestORM@test');

  //Pobieranie danych dla PBX_REPORT_EXTENSION
  Route::get('/pbx_report_ext', 'PBXDataAPI@PBXReportExtension');

    //Dane DKJ dla oddziałów
    Route::get('/TeamDKJHourData', 'PBXDataAPI@TeamDKJHourData');
    //Dane Czasu na rekord
    Route::get('/TimeOnRecordData', 'PBXDataAPI@TimeOnRecordData');

