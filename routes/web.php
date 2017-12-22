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

Route::get('/', function () {
    return redirect('home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/map/{x}/{y}', 'MapController@show');
Route::resource('a', 'AvatarController');
Route::resource('map', 'MapController');
Route::resource('schedule', 'ScheduleController');
Route::resource('activity', "ActivityController");
Route::resource('ActivityType', "ActivityTypeController");
Route::resource('building', 'BuildingController');
Route::resource('BuildingType', 'BuildingTypeController');
