<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
    return view('welcome');
});

Route::get('/admin', 'AdminController@index');

Route::get('/superadmin','SuperAdminController@index');

Auth::routes();
Route::group(['prefix' => 'admin','middleware' => ['auth']], function () {

   Route::get('dashboard', 'DashboardController@index')->name('dashboard');
   Route::group(['middleware' => ['role:ROLE_SUPERADMIN']], function () {
//       Roles

       Route::get('roles','RoleController@index')->name('manage_roles');
       Route::post('AddRole', 'RoleController@store')->name('AddRole');
       Route::get('RoleTable', 'RoleController@datatable')->name('Roletable');
       Route::get('RoleDelete/{id}', 'RoleController@delete')->name('RoleDelete');
       Route::get('AssignModules/{id}', 'RoleController@assignModules')->name('assignModules');
       Route::post('AssignModules', 'RoleController@storeModules')->name('storeModules');

//       Manage Staff
       Route::get('manageStaff','StaffController@index')->name('manage_staff');
       Route::get('StaffTable', 'StaffController@datatable')->name('StaffTable');
       Route::post('AddStaff','StaffController@store')->name('AddStaff');
       Route::post('UpdateStaff','StaffController@update')->name('UpdateStaff');
       Route::get('DeleteStaff','StaffController@delete')->name('DeleteStaff');
       Route::get('getRoles','StaffController@getRoles')->name('getRoles');
       Route::get('getStaffData','StaffController@getStaffData')->name('getStaffData');

       //Manage Customers
       Route::get('/customer/datatable','CustomerController@datatable')->name('customer_datatable');
       Route::get('/customer/setPermissions/{id}','CustomerController@setPermissions')->name('customer_setPermissions');
       Route::post('/customer/setPermissions','CustomerController@storePermisssions')->name('store_customerPermissions');
       Route::resource('customer','CustomerController');
   });
});
Route::get('/home', 'HomeController@index')->name('home');
