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

Route::get('/overzichten/{startDate?}', 'OverzichtController@range');
//Route::get('/{startDate}', 'OverzichtController@range');
Route::get('/','OverzichtController@defaultRange');


Route::resource('periodes','PeriodeController');
Route::resource('user','UserController');
Route::resource('leerkracht','LeerkrachtController');

//authentication stuff
Auth::routes();
Route::get('auth/google', 'Auth\LoginController@redirectToProvider');
Route::get('auth/google/callback', 'Auth\LoginController@handleProviderCallback');


//TODO: move to API-routes
Route::post('/periodes/checkForConflict', 'PeriodeController@checkForConflict');
Route::post('/periodes/getConflictingDays', 'OverzichtController@rangeForLeerkrachten');
Route::post('/periodes/calculateAantalDagdelen','PeriodeController@calculateAantalDagdelen');
Route::post('/periodes/startWeekschemaNr','PeriodeController@getStartWeekschemaNr');
Route::post('/periodes/getConflictingDays','PeriodeController@getConflictingDays');
Route::post('/periodes/getOpdrachtBreuk','PeriodeController@getOpdrachtBreuk');
