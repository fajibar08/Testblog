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

Route::get('/', 'PagesController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/home', 'HomeController@store_comments');
Route::post('/home/{post_id}', 'HomeController@store_reply');
Route::delete('/home/{post_id}', 'DeleteCommentsController@delete_comment');
Route::delete('/home/{post_id}/delete', 'DeleteCommentsController@delete_reply');

Route::resource('dashboard', 'DashboardController');
Route::resource('manage-profile', 'ProfileController');
