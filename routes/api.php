<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:api')->prefix('v1')->namespace('Api\V1')->group(function () {

    Route::apiResource('users', 'UsersController');
    Route::get('get-user-roles', 'UsersController@getRolesList');
    Route::apiResource('students', 'StudentController');
    Route::apiResource('students-groups', 'StudentsGroupController');
    Route::apiResource('study-subjects', 'StudySubjectsController');
    Route::apiResource('study-weeks', 'StudyWeekController');
    Route::post('study-weeks/mass-update', 'StudyWeekController@massUpdate');
    Route::apiResource('study-days', 'StudyDaysController');
    Route::apiResource('study-classes', 'StudyClassController');
    Route::get('get-current-weeks', 'StudyWeekController@getCurrentWeeks');


});

