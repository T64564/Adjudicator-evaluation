<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //

Route::get('adjudicators/import_csv',
    ['as' => 'adjudicators.import_csv', 'uses' => 'AdjudicatorController@getImport']);
Route::post('adjudicators/import_csv',
    ['as' => 'adjudicators.import_csv', 'uses' => 'AdjudicatorController@postImport']);

Route::get('teams/import_csv',
    ['as' => 'teams.import_csv', 'uses' => 'TeamController@getImport']);
Route::post('teams/import_csv',
    ['as' => 'teams.import_csv', 'uses' => 'TeamController@postImport']);

Route::resource('adjudicators', 'AdjudicatorController');
Route::resource('teams', 'TeamController');
Route::resource('rounds', 'RoundController');
});
