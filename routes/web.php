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

Route::get('/overzichten', 'OverzichtController@defaultRange');
Route::get('/', 'OverzichtController@defaultRange');
Route::post('/periodes/checkForConflict', 'PeriodeController@checkForConflict');

Route::resource('periodes','PeriodeController');

/*
Route::get('/periodes/create/{leerkracht}', 'PeriodeController@create');
Route::get('/periodes/{periode}/edit', 'PeriodeController@edit');

Route::post('/periodes', 'PeriodeController@store');
Route::patch('/periodes/{periode}', 'PeriodeController@update');

Route::delete('/periodes/{periode}', 'PeriodeController@delete');

Route::resource()
*/

Auth::routes();
Route::get('auth/google', 'Auth\LoginController@redirectToProvider');
Route::get('auth/google/callback', 'Auth\LoginController@handleProviderCallback');


Route::get('/home', 'HomeController@index')->name('home');
