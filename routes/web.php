<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'PagesController@root')->name('root');

/*
  // 用戶身份驗證相關的路由
  GET|HEAD   login .............. login › Auth\LoginController@showLoginForm
  POST       login .............. Auth\LoginController@login
  POST       logout ............. logout › Auth\LoginController@logout

  // 用戶註冊相關路由
  GET|HEAD   register .................. register › Auth\RegisterController@showRegistrationForm
  POST       register .................. Auth\RegisterController@register

  // 密碼重置相關路由
  POST       password/email ..... password.email › Auth\ForgotPasswordController@sendResetLinkEmail
  GET|HEAD   password/reset ..... password.request › Auth\ForgotPasswordController@showLinkRequestForm
  POST       password/reset ..... password.update › Auth\ResetPasswordController@reset
  GET|HEAD   password/reset/{token} .... password.reset › Auth\ResetPasswordController@showResetForm

  // 再次確認密碼（重要操作前提示）
  GET|HEAD   password/confirm ... password.confirm › Auth\ConfirmPasswordController@showConfirmForm
  POST       password/confirm ... Auth\ConfirmPasswordController@confirm
*/
// Auth::routes(); //用下面方式取代

// 用戶身份驗證相關的路由
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// 用用戶註冊相關路由
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// 密碼重置相關路由
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// 再次確認密碼（重要操作前提示）
Route::get('password/confirm', 'Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
Route::post('password/confirm', 'Auth\ConfirmPasswordController@confirm');

// Email 認證相關路由
Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

/*個人頁面
  GET|HEAD     users/{user} .......... users.show › UsersController@show
  PUT|PATCH    users/{user} .......... users.update › UsersController@update
  GET|HEAD     users/{user}/edit ..... users.edit › UsersController@edit

  Route::get('/users/{user}', 'UsersController@show')->name('users.show');
  Route::get('/users/{user}/edit', 'UsersController@edit')->name('users.edit');
  Route::patch('/users/{user}', 'UsersController@update')->name('users.update');
*/
Route::resource('users', 'UsersController', ['only' => ['show', 'update', 'edit']]);

/*帖子
  GET|HEAD        topics ...................... topics.index › TopicsController@index
  POST            topics ...................... topics.store › TopicsController@store
  GET|HEAD        topics/create ............... topics.create › TopicsController@create
  GET|HEAD        topics/{topic} .............. topics.show › TopicsController@show
  PUT|PATCH       topics/{topic} .............. topics.update › TopicsController@update
  DELETE          topics/{topic} .............. topics.destroy › TopicsController@destroy
  GET|HEAD        topics/{topic}/edit ......... topics.edit › TopicsController@edit
*/
Route::resource('topics', 'TopicsController', ['only' => ['index', 'show', 'create', 'store', 'update', 'edit', 'destroy']]);
