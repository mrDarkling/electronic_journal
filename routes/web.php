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
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/students', 'StudentsController@index')->name('students');
Route::get('/students-groups', 'StudentsGroupsController@index')->name('students-groups');
Route::get('/study-subjects', 'StudySubjectsController@index')->name('subjects');
Route::get('/users', 'UsersController@index')->name('users');
Route::get('/study-weeks', 'StudyWeeksController@index')->name('study-weeks');
