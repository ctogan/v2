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
Route::post('/admin/part-time/faq/update', 'AdminPartTimeController@faq_update');
Route::post('/admin/part-time/faq/delete', 'AdminPartTimeController@faq_delete');
