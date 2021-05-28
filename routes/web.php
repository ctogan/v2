<?php

use Illuminate\Support\Facades\Route;
use App\Helpers\Cache;

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

Route::get('/', function () {
    return view('home');
})->middleware('auth');

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

//admin
Route::get('/admin/part-time/vacancy', 'AdminPartTimeController@vacancy')->name('admin.pt.vacancy');
Route::get('/admin/part-time/vacancy/add', 'AdminPartTimeController@vacancy_add')->name('admin.pt.vacancy_add');
Route::get('/admin/part-time/vacancy/edit/{id}', 'AdminPartTimeController@vacancy_edit')->name('admin.pt.vacancy_edit');
Route::get('/admin/part-time/vacancy/reported', 'AdminPartTimeController@vacancy_reported')->name('admin.pt.vacancy_reported');
Route::post('/admin/part-time/vacancy/reported/paging', 'AdminPartTimeController@vacancy_reported_paging')->name('admin.pt.vacancy_reported_paging');
Route::post('/admin/part-time/vacancy/paging', 'AdminPartTimeController@vacancy_paging')->name('admin.pt.vacancy_paging');
Route::post('/admin/part-time/vacancy/submit', 'AdminPartTimeController@vacancy_submit')->name('admin.pt.vacancy_submit');
Route::post('/admin/part-time/vacancy/update', 'AdminPartTimeController@vacancy_update')->name('admin.pt.vacancy_update');
Route::post('/admin/part-time/vacancy/delete', 'AdminPartTimeController@vacancy_delete')->name('admin.pt.vacancy_delete');
Route::post('/admin/part-time/vacancy/approve', 'AdminPartTimeController@vacancy_approve')->name('admin.pt.vacancy_approve');
Route::post('/admin/part-time/vacancy/reject', 'AdminPartTimeController@vacancy_reject')->name('admin.pt.vacancy_reject');

Route::get('/admin/part-time/company', 'AdminPartTimeController@company')->name('admin.pt.vacancy_add');
Route::get('/admin/part-time/company/add', 'AdminPartTimeController@company_add')->name('admin.pt.vacancy_add');
Route::get('/admin/part-time/company/edit/{id}', 'AdminPartTimeController@company_edit')->name('admin.pt.company_edit');
Route::post('/admin/part-time/company/paging', 'AdminPartTimeController@company_paging')->name('admin.pt.company_paging');
Route::post('/admin/part-time/company/submit', 'AdminPartTimeController@company_submit')->name('admin.pt.company_submit');
Route::post('/admin/part-time/company/update', 'AdminPartTimeController@company_update')->name('admin.pt.company_update');
Route::post('/admin/part-time/company/delete', 'AdminPartTimeController@company_delete')->name('admin.pt.company_delete');

Route::get('/admin/part-time/applicant', 'AdminPartTimeController@applicant')->name('admin.pt.applicant');
Route::post('/admin/part-time/applicant/paging', 'AdminPartTimeController@applicant_paging')->name('admin.pt.applicant_paging');

Route::get('/admin/part-time/applicant/reported', 'AdminPartTimeController@applicant_reported')->name('admin.pt.applicant_reported');

Route::get('/admin/data/city/', 'AdminPartTimeController@company')->name('admin.pt.company');
Route::get('/admin/data/city/{id}', function ($id) {
    return Cache::get_city($id);
});
Route::get('/admin/data/company/{id}', function ($id) {
    return Cache::get_company_by_id($id);
});

//master
Route::get('/admin/master/province', 'ProvinceController@index')->name('province.index');
Route::get('/admin/master/province/add', 'ProvinceController@add')->name('province.add');
Route::get('/admin/master/province/edit/{id}', 'ProvinceController@edit')->name('province.edit');
Route::post('/admin/master/province/submit', 'ProvinceController@submit')->name('province.submit');
Route::post('/admin/master/province/update', 'ProvinceController@update')->name('province.update');
Route::post('/admin/master/province/delete', 'ProvinceController@delete')->name('province.delete');

Route::get('/admin/master/city', 'CityController@index')->name('city.index');
Route::get('/admin/master/city/add', 'CityController@add')->name('city.add');
Route::get('/admin/master/city/edit/{id}', 'CityController@edit')->name('city.edit');
Route::post('/admin/master/city/submit', 'CityController@submit')->name('city.submit');
Route::post('/admin/master/city/update', 'CityController@update')->name('city.update');
Route::post('/admin/master/city/delete', 'CityController@delete')->name('city.delete');

Route::get('/admin/master/company-category', 'CompanyCategoryController@index')->name('company.category');
Route::get('/admin/master/company-category/add', 'CompanyCategoryController@add')->name('company.category_add');
Route::get('/admin/master/company-category/edit/{id}', 'CompanyCategoryController@edit')->name('company.category.edit');
Route::post('/admin/master/company-category/submit', 'CompanyCategoryController@submit')->name('company.category.submit');
Route::post('/admin/master/company-category/update', 'CompanyCategoryController@update')->name('company.category.update');
Route::post('/admin/master/company-category/delete', 'CompanyCategoryController@delete')->name('company.category.delete');

Route::post('/admin/master/province/paging', 'ProvinceController@paging')->name('province.paging');
Route::post('/admin/master/city/paging', 'CityController@paging')->name('city.paging');
Route::post('/admin/master/company-category/paging', 'CompanyCategoryController@paging')->name('company.category.paging');

Route::get('/admin/settings/change-password', function () {
    return view('admin.settings.change_password');
})->middleware('auth');

Route::get('/admin/settings/user-admin', 'UserAdminController@index')->name('admin.user.index');
Route::get('/admin/settings/user-admin/add', 'UserAdminController@add')->name('admin.user.add');
Route::get('/admin/settings/user-admin/edit/{id}', 'UserAdminController@edit')->name('admin.user.edit');
Route::post('/admin/settings/user-admin/submit', 'UserAdminController@submit')->name('admin.user.submit');
Route::post('/admin/settings/user-admin/update', 'UserAdminController@update')->name('admin.user.update');
Route::post('/admin/settings/user-admin/delete', 'UserAdminController@delete')->name('admin.user.delete');

//operational
Route::get('/admin/operational/clbk/event', 'OperationalController@clbk_event')->name('admin.operational.clbk.event');
Route::get('/admin/operational/clbk/event/upload', 'OperationalController@clbk_upload')->name('admin.operational.clbk.event.upload');
Route::post('/admin/operational/clbk/event/submit', 'OperationalController@clbk_submit')->name('admin.operational.clbk.event.submit');

Route::get('/sms-job', 'OperationalController@do_sms_queue')->name('sms.job');

Route::get('/cv', 'AdminPartTimeController@cv')->name('cv');

Route::get('/admin/part-time/faq', 'AdminPartTimeController@faq');
Route::get('/admin/part-time/faq/add', 'AdminPartTimeController@faq_add');
Route::get('/admin/part-time/faq/edit/{id}', 'AdminPartTimeController@faq_edit');
Route::post('/admin/part-time/faq/paging', 'AdminPartTimeController@faq_paging');
Route::post('/admin/part-time/faq/submit', 'AdminPartTimeController@faq_submit');
Route::post('/admin/part-time/faq/submit', 'AdminPartTimeController@faq_submit');
Route::post('/admin/part-time/faq/update', 'AdminPartTimeController@faq_update');
Route::post('/admin/part-time/faq/delete', 'AdminPartTimeController@faq_delete');


Route::get('/admin/operation/banner', 'BannerController@index');
Route::get('/admin/operation/banner/edit/{id}', 'BannerController@get');
Route::post('/admin/operation/banner/paging', 'BannerController@paging');
Route::post('/admin/operation/banner/submit', 'BannerController@submit');
Route::post('/admin/operation/banner/update', 'BannerController@update');
Route::post('/admin/operation/banner/delete', 'BannerController@delete');
Route::post('/admin/operation/banner/position/update', 'BannerController@update_position');

Route::get('/admin/operation/category', 'CategoryController@index');
Route::get('/admin/operation/category/edit/{id}', 'CategoryController@get');
Route::post('/admin/operation/category/paging', 'CategoryController@paging');
Route::post('/admin/operation/category/submit', 'CategoryController@submit');
Route::post('/admin/operation/category/update', 'CategoryController@update');
Route::post('/admin/operation/category/delete', 'CategoryController@delete');
Route::post('/admin/operation/category/position/update', 'CategoryController@update_position');

Route::get('/admin/operation/theme', 'OperationalController@theme');
Route::post('/admin/operation/theme/update', 'OperationalController@theme_update');

Route::get('/admin/news', 'NewsController@index');
Route::post('/admin/news/paging', 'NewsController@paging');
Route::get('/admin/news/add', 'NewsController@add');
Route::get('/admin/news/edit/{id}', 'NewsController@edit');
Route::post('/admin/news/submit', 'NewsController@submit');
Route::post('/admin/news/update', 'NewsController@update');
Route::post('/admin/news/delete', 'NewsController@delete');

Route::get('/admin/flash-event','FlashEventController@index');
Route::get('/admin/flash-event/add','FlashEventController@add');
Route::get('/admin/flash-event/{id}','FlashEventController@edit');
Route::post('/admin/flash-event/submit','FlashEventController@submit');
Route::post('/admin/flash-event/update','FlashEventController@update');
Route::post('/admin/flash-event/paging','FlashEventController@paging');
Route::post('/admin/flash-event/delete','FlashEventController@delete');
Route::post('/admin/flash-event-detail/delete', 'FlashEventController@delete_detail');

Route::get('/admin/dynamic-section','DynamicSectionController@index');
Route::get('/admin/dynamic-section/edit/{id}','DynamicSectionController@edit');
Route::post('/admin/dynamic-section/submit','DynamicSectionController@submit');
Route::post('/admin/dynamic-section/paging','DynamicSectionController@paging');
Route::post('/admin/dynamic-section/update','DynamicSectionController@update');
Route::post('/admin/dynamic-section/delete','DynamicSectionController@delete');

Route::get('/admin/cerdas-cermat','CerdasCermatController@index');
Route::get('/admin/cerdas-cermat/add','CerdasCermatController@add');
Route::get('/admin/cerdas-cermat/edit/{id}','CerdasCermatController@edit');
Route::post('/admin/cerdas-cermat/submit','CerdasCermatController@submit');
Route::post('/admin/cerdas-cermat/update','CerdasCermatController@update');
Route::post('/admin/cerdas-cermat/paging','CerdasCermatController@paging');
Route::post('/admin/cerdas-cermat/delete','CerdasCermatController@delete');
Route::get('/admin/cerdas-cermat/question','CerdasCermatController@question_list');
Route::post('/admin/cerdas-cermat/question/submit','CerdasCermatController@submit_question');
Route::post('/admin/cerdas-cermat/question/update','CerdasCermatController@update_question');
Route::post('/admin/cerdas-cermat/question/delete','CerdasCermatController@delete_question');
Route::post('/admin/cerdas-cermat/question/paging','CerdasCermatController@question_paging');
Route::post('/admin/cerdas-cermat/question/random_paging','CerdasCermatController@random_paging');
Route::get('/admin/cerdas-cermat/question/edit/{id}','CerdasCermatController@get_question');
Route::post('/admin/cerdas-cermat/answer/delete','CerdasCermatController@delete_answer');
Route::post('/admin/cerdas-cermat/session-paging','CerdasCermatController@session_paging');
Route::get('/admin/cerdas-cermat/edit/{id}','CerdasCermatController@edit');
Route::post('/admin/cerdas-cermat/update','CerdasCermatController@update');
Route::post('/admin/cerdas-cermat/prize/delete','CerdasCermatController@delete_prize');

Route::get('/admin/product','ProductController@index');
Route::get('/admin/product/edit/{id}','ProductController@get');
Route::post('/admin/product/paging','ProductController@paging');
Route::post('/admin/product/update','ProductController@update');

Route::get('/admin/notification', 'NotificationController@index');
Route::post('/admin/notification/submit', 'NotificationController@send');
Route::post('/admin/notification/paging', 'NotificationController@paging');
Route::post('/admin/notification/paging/detail', 'NotificationController@paging_detail');
Route::get('/admin/notification/{id}', 'NotificationController@detail');


Route::get('/app/cerdas-cermat', 'Webapp\CerdasCermatController@index');
Route::get('/app/cerdas-cermat/start/{code}', 'Webapp\CerdasCermatController@start');