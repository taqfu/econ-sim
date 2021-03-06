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
Route::get('/admin', function (){
    if (Auth::guest() || (Auth::user() && Auth::user()->id!=1)){
        return "Nope. Nothing here, bro.";
    }
    return view('admin');
})->name('admin');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/map/{x}/{y}', 'MapController@show');
Route::post('job/{id}/apply', 'JobController@apply')->name('job.apply');
Route::resource('a', 'AvatarController');
Route::resource('activity', "ActivityController");
Route::resource('ActivityType', "ActivityTypeController");
Route::resource('building', 'BuildingController');
Route::resource('BuildingType', 'BuildingTypeController');
Route::resource('ItemType', 'ItemTypeController');
Route::resource('JobType', 'JobTypeController');
Route::resource('job', 'JobController');
Route::resource('map', 'MapController');
Route::resource('room', 'RoomController');
Route::resource('RoomType', 'RoomTypeController');
Route::resource('schedule', 'ScheduleController');
Route::resource('shipment', 'ShipmentController');

Route::get('a/{avatar_id}/schedule', 'ScheduleController@index')->name('schedule.index');
