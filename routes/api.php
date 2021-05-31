<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//API
Route::get('/master/province', 'ApiMasterController@get_province')->name('api.master.province')->middleware('localization');
Route::get('/master/city', 'ApiMasterController@get_city')->name('api.master.city')->middleware('localization');
Route::get('/master/all/location', 'ApiMasterController@get_all_locate')->name('api.master.all.location')->middleware('localization');
Route::get('/master/notification', 'ApiMasterController@get_notification')->name('api.master.notification')->middleware('localization');
Route::get('/master/company-category', 'ApiMasterController@get_company_category')->name('api.master.company_category')->middleware('localization');
Route::get('/master/main-category', 'ApiMasterController@get_main_company_category')->name('api.master.main_category')->middleware('localization');
Route::get('/master/education', 'ApiMasterController@get_education')->name('api.master.get_education')->middleware('localization');

Route::get('/part-time', 'ApiPartTimeController@index')->name('api.pt.index')->middleware('localization');
Route::get('/part-time/candidate', 'ApiPartTimeController@candidate')->name('api.pt.candidate')->middleware('localization');
Route::get('/part-time/candidate/history', 'ApiPartTimeController@candidate_history')->name('api.pt.candidate_history')->middleware('localization');
Route::get('/part-time/company', 'ApiPartTimeController@company')->name('api.pt.company')->middleware('localization');
Route::get('/part-time/company/history', 'ApiPartTimeController@company_history')->name('api.pt.company')->middleware('localization');
Route::get('/part-time/company/detail', 'ApiPartTimeController@company_detail')->name('api.pt.detail')->middleware('localization');
Route::get('/part-time/filter/get', 'ApiPartTimeController@search_filter')->name('api.pt.search_filter')->middleware('localization');
Route::get('/part-time/search', 'ApiPartTimeController@search')->name('api.pt.search')->middleware('localization');
Route::get('/part-time/vacancy/detail', 'ApiPartTimeController@vacancy_detail')->name('api.pt.vacancy_detail')->middleware('localization');
Route::get('/part-time/candidate/bookmark/get', 'ApiPartTimeController@get_bookmark')->name('api.pt.get_bookmark')->middleware('localization');
Route::get('/part-time/home', 'ApiPartTimeController@candidate_home')->name('api.pt.home')->middleware('localization');

Route::get('/part-time/mycompany', 'ApiPartTimeController@my_company')->name('api.pt.mycompany')->middleware('localization');
Route::get('/part-time/mycompany/detail', 'ApiPartTimeController@my_company_detail')->name('api.pt.mycompanydetail')->middleware('localization');
Route::get('/part-time/mycompany/candidate/bookmark', 'ApiPartTimeController@my_company_candidate_bookmark')->name('api.pt.mycompany.candidate.bookmark')->middleware('localization');

//FOR JOB PROVIDER
Route::get('/part-time/applicant/candidate', 'ApiPartTimeController@applicant_candidate')->name('api.pt.applicant_candidate')->middleware('localization');
//web view
Route::get('/part-time/view/location', 'ApiPartTimeController@view_location')->name('api.pt.applicant_candidate')->middleware('localization');
Route::get('/web/filter/location/province', 'ApiPartTimeController@filter_location_province')->name('api.pt.filter.location')->middleware('localization');



Route::post('/part-time/filter/submit', 'ApiPartTimeController@submit_filter')->name('api.pt.submit_filter')->middleware('localization');
Route::post('/part-time/vacancy/apply', 'ApiPartTimeController@apply_vacancy')->name('api.pt.apply_vacancy')->middleware('localization');
Route::post('/part-time/candidate/profile/submit', 'ApiPartTimeController@submit_candidate_profile')->name('api.pt.submit_candidate_profile')->middleware('localization');
Route::post('/part-time/candidate/profile', 'ApiPartTimeController@candidate_profile')->name('api.pt.candidate_profile')->middleware('localization');

Route::post('/part-time/candidate/profile/experiences', 'ApiPartTimeController@submit_candidate_experiences')->name('api.pt.candidate_profile_experience')->middleware('localization');
Route::post('/part-time/candidate/profile/experiences/delete', 'ApiPartTimeController@delete_candidate_experiences')->name('api.pt.candidate_profile_experience')->middleware('localization');

Route::post('/part-time/candidate/bookmark/submit', 'ApiPartTimeController@submit_vacancy_bookmark')->name('api.pt.submit_bookmark')->middleware('localization');
Route::post('/part-time/candidate/bookmark/delete', 'ApiPartTimeController@delete_vacancy_bookmark')->name('api.pt.delete_bookmark')->middleware('localization');
Route::post('/part-time/vacancy/report/submit', 'ApiPartTimeController@submit_report_vacancy')->name('api.pt._submit_report')->middleware('localization');
Route::post('/part-time/candidate/report/submit', 'ApiPartTimeController@submit_report_candidate')->name('api.pt._submit_report')->middleware('localization');

Route::post('/part-time/candidate/bookmark/candidate/submit', 'ApiPartTimeController@submit_candidate_bookmark')->name('api.pt.submit_bookmark')->middleware('localization');
Route::post('/part-time/candidate/bookmark/candidate/delete', 'ApiPartTimeController@delete_candidate_bookmark')->name('api.pt.delete_bookmark')->middleware('localization');


Route::post('/part-time/company/profile/submit', 'ApiPartTimeController@submit_company_profile')->name('api.pt._submit_company_profile')->middleware('localization');
Route::post('/part-time/company/profile/logo/submit', 'ApiPartTimeController@submit_company_profile_logo')->name('api.pt._submit_company_profile_logo')->middleware('localization');

Route::post('/part-time/vacancy/submit', 'ApiPartTimeController@submit_vacancy')->name('api.pt.submit_vacancy')->middleware('localization');
Route::post('/part-time/vacancy/update', 'ApiPartTimeController@update_vacancy')->name('api.pt.update_vacancy')->middleware('localization');

//END API

Route::get('/part-time/employe/form', 'ApiPartTimeController@form_employer')->name('api.pt.employe.form')->middleware('localization');

Route::post('/part-time/preference/province/submit', 'ApiPartTimeController@insert_preference_province')->name('api.pt.preference.province.submit')->middleware('localization');
Route::post('/part-time/preference/education/submit', 'ApiPartTimeController@insert_preference_education')->name('api.pt.preference.education.submit')->middleware('localization');
Route::post('/part-time/preference/category/submit', 'ApiPartTimeController@insert_preference_category')->name('api.pt.preference.category.submit')->middleware('localization');

Route::post('/part-time/candidate/profile/image/save', 'ApiPartTimeController@upload_image_profile')->name('api.pt.upload_image_profile')->middleware('localization');

Route::get('/part-time/generate/cv', 'ApiPartTimeController@generate_cv')->name('api.pt.generate_cv')->middleware('localization');
Route::get('/part-time/faq', 'ApiPartTimeController@faq')->name('api.pt.faq')->middleware('localization');

Route::get('/home', 'Api\HomeController@index');

//News
Route::get('/news', 'Api\NewsController@index');
Route::get('/news/detail', 'Api\NewsController@get');
Route::post('/news/point', 'Api\NewsController@point');

//Flash Event
Route::get('/flash-event/detail', 'Api\FlashEventController@get_flash_event');

//Cerdas Cermat
Route::get('cerdas-cermat', 'Api\CerdasCermatController@index');
Route::get('cerdas-cermat/detail', 'Api\CerdasCermatController@get');
Route::post('cerdas-cermat/register', 'Api\CerdasCermatController@register');

//Notification
Route::get('/notification', 'Api\NotificationController@index');
Route::post('/notification/read', 'Api\NotificationController@read');

//Search
Route::get('/search/recommendation', 'Api\SearchController@index');
Route::get('/search', 'Api\SearchController@search');

//entertainment
Route::get('/entertainment', 'Api\EntertainmentController@index');

//user
Route::post('/user/auth/check-phone-number', 'Api\UserController@check_phone_number');
Route::post('/user/auth/verify-otp', 'Api\UserController@verify_otp');
Route::post('/user/auth/request-otp', 'Api\UserController@request_otp');
Route::post('/user/auth/login/email', 'Api\UserController@login_email');
Route::post('/user/auth/login/phone', 'Api\UserController@login_phone');
Route::post('/user/auth/register', 'Api\UserController@register');