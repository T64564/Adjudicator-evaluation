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
    Route::get('adjudicators/sample_csv',
        ['as' => 'adjudicators.sample_csv',
        'uses' => 'AdjudicatorController@sampleCsv']);

    Route::get('teams/import_csv',
        ['as' => 'teams.import_csv',
        'uses' => 'TeamController@getImport']);
    Route::post('teams/import_csv',
        ['as' => 'teams.import_csv',
        'uses' => 'TeamController@postImport']);
    Route::get('teams/sample_csv',
        ['as' => 'teams.sample_csv',
        'uses' => 'TeamController@sampleCsv']);

    Route::get('feedbacks/',
        ['as' => 'feedbacks.index',
        'uses' => 'FeedbackController@index']);
    Route::get('feedbacks/{round}/enter_results',
        ['as' => 'feedbacks.enter_results',
        'uses' => 'FeedbackController@enterResults']);

    Route::get('feedbacks/{round}/create_team_to_adj',
        ['as' => 'feedbacks.create_team_to_adj',
        'uses' => 'FeedbackController@createTeamToAdj']);
    Route::get('feedbacks/{round}/create_adj_to_adj',
        ['as' => 'feedbacks.create_adj_to_adj',
        'uses' => 'FeedbackController@createAdjToAdj']);

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
    Route::get('feedbacks/{round}/check_asian',
        ['as' => 'feedbacks.check_asian',
        'uses' => 'FeedbackController@checkAsian']);
    Route::get('feedbacks/{round}/check_bp',
        ['as' => 'feedbacks.check_bp',
        'uses' => 'FeedbackController@checkBp']);

    Route::get('results/ranking',
        ['as' => 'results.ranking',
        'uses' => 'ResultController@ranking']);
    Route::get('results/ranking/export_csv',
        ['as' => 'results.ranking.export_csv',
        'uses' => 'ResultController@getExport']);

    Route::resource('adjudicators', 'AdjudicatorController');
    Route::resource('teams', 'TeamController');
    Route::resource('rounds', 'RoundController');


    Route::get('backup', ['as' => 'backup', function () {
        $db_user = env('DB_USERNAME', 'root');
        $db_pass = env('DB_PASSWORD', '');
        $cmd = '/Applications/XAMPP/bin/mysqldump -u'
            . $db_user . ' -p' . $db_pass . ' adjudicator_evaluation';
        $output = shell_exec($cmd);
        $file_name = 'dump.sql';
        $headers = array(
            'Content-Type' => 'text/sql',
            'Content-Disposition' => "attachment; filename=$file_name",
        );
        return \Response::make($output, 200, $headers);
    }]);

    Route::get('restore', ['as' => 'restore', function () {
        return view('restore');
    }]);

    Route::post('restore', function () {
        $file = \Input::file('sql');
        if (!isset($file)) {
            \Session::flash('flash_danger', "Please specify a file.");
            return view('restore');
        }

        $fileName = $file->getClientOriginalName();
        $move = $file->move(storage_path(). '/upload', $fileName);

        $db_user = env('DB_USERNAME', 'root');
        $db_pass = env('DB_PASSWORD', '');
        $cmd = '/Applications/XAMPP/bin/mysql -u'
            . $db_user . ' -p' . $db_pass . ' adjudicator_evaluation < ' . $move;
        echo exec($cmd);
        return redirect()->route('restore');
    });
});
