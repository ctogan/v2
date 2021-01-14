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
Route::get('/master/company-category', 'ApiMasterController@get_company_category')->name('api.master.company_category')->middleware('localization');
Route::get('/part-time', 'ApiPartTimeController@index')->name('api.pt.index')->middleware('localization');
Route::get('/part-time/candidate', 'ApiPartTimeController@candidate')->name('api.pt.candidate')->middleware('localization');
Route::get('/part-time/candidate/history', 'ApiPartTimeController@candidate_history')->name('api.pt.candidate_history')->middleware('localization');
Route::get('/part-time/company', 'ApiPartTimeController@company')->name('api.pt.company')->middleware('localization');
Route::get('/part-time/company/detail', 'ApiPartTimeController@company')->name('api.pt.detail')->middleware('localization');
Route::get('/part-time/filter/get', 'ApiPartTimeController@search_filter')->name('api.pt.search_filter')->middleware('localization');
Route::get('/part-time/search', 'ApiPartTimeController@search')->name('api.pt.search')->middleware('localization');
Route::get('/part-time/vacancy/detail', 'ApiPartTimeController@vacancy_detail')->name('api.pt.vacancy_detail')->middleware('localization');
Route::get('/part-time/candidate/bookmark/get', 'ApiPartTimeController@get_bookmark')->name('api.pt.get_bookmark')->middleware('localization');
Route::get('/part-time/home', 'ApiPartTimeController@candidate_home')->name('api.pt.home')->middleware('localization');
//FOR JOB PROVIDER
Route::get('/part-time/applicant/candidate', 'ApiPartTimeController@applicant_candidate')->name('api.pt.applicant_candidate')->middleware('localization');
//web view
Route::get('/part-time/view/location', 'ApiPartTimeController@view_location')->name('api.pt.applicant_candidate')->middleware('localization');

Route::post('/part-time/filter/submit', 'ApiPartTimeController@submit_filter')->name('api.pt.submit_filter')->middleware('localization');
Route::post('/part-time/vacancy/apply', 'ApiPartTimeController@apply_vacancy')->name('api.pt.apply_vacancy')->middleware('localization');
Route::post('/part-time/candidate/profile/submit', 'ApiPartTimeController@submit_candidate_profile')->name('api.pt.submit_candidate_profile')->middleware('localization');
Route::post('/part-time/candidate/profile', 'ApiPartTimeController@candidate_profile')->name('api.pt.candidate_profile')->middleware('localization');

Route::post('/part-time/candidate/profile/experiences', 'ApiPartTimeController@submit_candidate_experiences')->name('api.pt.candidate_profile_experience')->middleware('localization');

Route::post('/part-time/candidate/bookmark/submit', 'ApiPartTimeController@submit_vacancy_bookmark')->name('api.pt.submit_bookmark')->middleware('localization');
Route::post('/part-time/candidate/bookmark/delete', 'ApiPartTimeController@delete_vacancy_bookmark')->name('api.pt.delete_bookmark')->middleware('localization');
Route::post('/part-time/vacancy/report/submit', 'ApiPartTimeController@submit_report_vacancy')->name('api.pt._submit_report')->middleware('localization');
Route::post('/part-time/company/profile/submit', 'ApiPartTimeController@submit_company_profile')->name('api.pt._submit_company_profile')->middleware('localization');
Route::post('/part-time/vacancy/submit', 'ApiPartTimeController@submit_vacancy')->name('api.pt.submit_vacancy')->middleware('localization');
Route::post('/part-time/vacancy/update', 'ApiPartTimeController@update_vacancy')->name('api.pt.update_vacancy')->middleware('localization');
//END API