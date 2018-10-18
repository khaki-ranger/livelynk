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

// 基本設定 vendor\laravel\framework\src\Illuminate\Routing\Router.php  ->auth()
// Auth::routes();

// Authentication Routes...
Route::get('/login/{path?}', 'Auth\LoginController@show')->name('login');
Route::post('/login/{path?}', 'Auth\LoginController@authenticate');
Route::post("logout", 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('/register/{path?}', 'Auth\RegisterController@show')->name('register');
Route::post('/register/{path?}', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get(env("PASSWORD_PATH").'/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post(env("PASSWORD_PATH").'/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get(env("PASSWORD_PATH").'/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post(env("PASSWORD_PATH").'/reset', 'Auth\ResetPasswordController@reset');

// password change
Route::get('/password/edit{id?}', 'ChangePasswordController@edit')->middleware('auth');
Route::post('/password/update', 'ChangePasswordController@update')->middleware('auth');

// 未ログイン時 && ログイン後 index画面  [未]welcome を表示  [後]滞在者一覧画面
// 未ログイン時は滞在者画面への遷移を作ってはいけない（プライバシー的な問題）
Route::get('/', 'IndexController@index');

// 未ログイン時の 滞在者一覧画面 コミュニティ毎のpathを知っているものだけが閲覧できる
// /index?path=hoge
Route::get('/index/{path?}', 'IndexController@index')->name('index');

// 管理画面 認証済みuserのみ表示
Route::group(['middleware' => ['auth', 'can:normalAdmin']], function () {
    Route::get('/admin_user', 'AdminUserController@index');
    Route::get('/admin_user/add', 'AdminUserController@add');
    Route::post('/admin_user/create', 'AdminUserController@create');
});
Route::get('/admin_user/edit{id?}', 'AdminUserController@edit')->middleware('auth');
Route::post('/admin_user/update', 'AdminUserController@update')->middleware('auth');
Route::get('/admin_user/delete{id?}', 'AdminUserController@delete')->middleware('auth');
Route::post('/admin_user/remove', 'AdminUserController@remove')->middleware('auth');


Route::get('/admin_mac_address', 'AdminMacAddressController@index')->middleware('auth');
Route::get('/admin_mac_address/edit{id?}', 'AdminMacAddressController@edit')->middleware('auth');
Route::post('/admin_mac_address/update', 'AdminMacAddressController@update')->middleware('auth');
Route::get('/admin_mac_address/delete{id?}', 'AdminMacAddressController@delete')->middleware('auth');
Route::post('/admin_mac_address/remove', 'AdminMacAddressController@remove')->middleware('auth');

// Admin only
Route::group(['middleware' => ['auth', 'can:normalAdmin']], function () {
    Route::get('/admin_router', 'AdminRouterController@index');
    Route::get('/admin_router/add', 'AdminRouterController@add');
    Route::post('/admin_router/create', 'AdminRouterController@create');
    Route::get('/admin_router/edit{id?}', 'AdminRouterController@edit');
    Route::post('/admin_router/update', 'AdminRouterController@update');
});

// superAdmin only
Route::group(['middleware' => ['auth', 'can:superAdmin']], function () {
    Route::get('/admin_community', 'AdminCommunityController@index');
    Route::get('/admin_community/add', 'AdminCommunityController@add');
    Route::post('/admin_community/create', 'AdminCommunityController@create');
});
// Admin only
Route::group(['middleware' => ['auth', 'can:normalAdmin']], function () {
    Route::get('/admin_community/edit{id?}', 'AdminCommunityController@edit');
    Route::post('/admin_community/update', 'AdminCommunityController@update');
});


// 外部からのPOST受け取り先 csrf off
Route::post('/inport_post/mac_address', 'InportPostController@MacAddress');

// test用
Route::post('/inport_post/test', 'InportPostController@DepartureCheck');


// 外部へのPOST送信 route必要?
Route::post('/push_ifttt_arraival', 'ExportPostController@push_ifttt_arraival');

// 送信メール本文のプレビュー
Route::get('sample/mailable/preview', function () {
    return new App\Mail\SampleNotification();
});

Route::get('sample/mailable/send', 'SampleController@SampleNotification');
