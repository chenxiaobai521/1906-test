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

//Route::get('/', function () {
//    return view('welcome');
//});
Route::any('weather','Admin\Indexcontroller@weather');
Route::any('weatherasd','Admin\Indexcontroller@weatherasd');
Route::get('/','Admin\Indexcontroller@index');
Route::view('login','admin\login');
Route::post('loginDo','Admin\Logincontroller@loginDo');
//-----------------------------------------------------------//
Route::get('show','Admin\Mediacontroller@show');
Route::get('create','Admin\Mediacontroller@create');
Route::post('store','Admin\Mediacontroller@store');
//-----------------------------------------------------------//
Route::any('/xinwen','Admin\Ceshicontroller@index');
Route::get('/xinwen/index','Admin\Xinwencontroller@index');
Route::get('/xinwen/create','Admin\Xinwencontroller@create');
Route::post('/xinwen/store','Admin\Xinwencontroller@store');
Route::get('/xinwen/edit/{id}','Admin\Xinwencontroller@edit');
Route::post('/xinwen/update/{id}','Admin\Xinwencontroller@update');
Route::get('/xinwen/destroy/{id}','Admin\Xinwencontroller@destroy');
//-----------------------------------------------------------//
Route::get('/channel/index','Admin\Channelcontroller@index');
Route::get('/channel/create','Admin\Channelcontroller@create');
Route::post('/channel/store','Admin\Channelcontroller@store');
Route::get('/channel/show','Admin\Channelcontroller@show');
//-----------------------------------------------------------//
Route::get('menu','Admin\Testcontroller@createMenu');//创建菜单

// 微信网页授权
Route::get('test','Admin\Wxcontroller@test');// 测试
Route::get('auth','Admin\Wxcontroller@auth');// 接收 code
