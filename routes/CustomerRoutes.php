<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::group(['prefix' => 'customer'], function () {
   Route::post('/login/post','Customer\LoginController@attemptLogin')->name('attemptLogin');
    Route::get('/login','Customer\LoginController@login')->name('clogin');
    Route::post('/logout','Customer\LoginController@logout')->name('clogout');
    Route::group(['middleware' => ['auth:customer']], function () {
     Route::get('/dashboard', 'Customer\CustomerController@index')->name('customer_dashbaord');
   });
});
