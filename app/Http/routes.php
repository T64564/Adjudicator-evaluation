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

    Route::get('adjudicators/import_csv',
        ['as' => 'adjudicators.import_csv', 
        'uses' => 'AdjudicatorController@getImport']);
    Route::post('adjudicators/import_csv',
        ['as' => 'adjudicators.import_csv', 
        'uses' => 'AdjudicatorController@postImport']);

    Route::get('teams/import_csv',
        ['as' => 'teams.import_csv',
        'uses' => 'TeamController@getImport']);
    Route::post('teams/import_csv',
        ['as' => 'teams.import_csv', 
        'uses' => 'TeamController@postImport']);

    Route::get('feedbacks/',
        ['as' => 'feedbacks.index',
        'uses' => 'FeedbackController@index']);
    Route::get('feedbacks/{round}/enter_results',
        ['as' => 'feedbacks.enter_results',
        'uses' => 'FeedbackController@enterResults']);
    Route::get('feedbacks/{round}/create',
        ['as' => 'feedbacks.create',
        'uses' => 'FeedbackController@create']);
    Route::post('feedbacks',
        ['as' => 'feedbacks.store',
        'uses' => 'FeedbackController@store']);
    Route::get('feedbacks/{round}/{feedback}/edit',
        ['as' => 'feedbacks.edit',
        'uses' => 'FeedbackController@edit']);
    Route::patch('feedbacks/{feedback}',
        ['as' => 'feedbacks.update',
        'uses' => 'FeedbackController@update']);
    Route::delete('feedbacks/{round}/{feedback}',
        ['as' => 'feedbacks.destroy',
        'uses' => 'FeedbackController@destroy']);
    Route::get('feedbacks/{round}/check',
        ['as' => 'feedbacks.check',
        'uses' => 'FeedbackController@check']);

    Route::get('result/ranking',
        ['as' => 'results.ranking',
        'uses' => 'ResultController@ranking']);

    Route::resource('adjudicators', 'AdjudicatorController');
    Route::resource('teams', 'TeamController');
    Route::resource('rounds', 'RoundController');
});
