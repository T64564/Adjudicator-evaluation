<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Team;

class TeamController extends Controller {

    public function index() {
        $heads = Team::getTableHeader();
        $values = Team::get();
        return view('teams.index', compact('heads', 'values'));
    }

    public function show() {
    }

    public function create() {
        return 'CREATE';
    }

    public function store() {
    }

    public function edit() {
    }

    public function update() {
    }

    public function destroy() {
    }

    public function getImport() {
        return view('teams.import_csv');
    }

    public function postImport() {
        $file = \Input::file('csv');
        if (!isset($file)) {
            \Session::flash('flash_danger', "Please specify a file.");
            return view('teams.import_csv');
        }

        /* add timestamp */
        $fileName = $file->getClientOriginalName(). '_'. time();
        $move = $file->move(storage_path(). '/upload', $fileName);

        $update = false;
        if (\Request('name_dep') === 'update') {
            $update = true;
        }
        \Debugbar::info(\Request::all());
        $model = new Team();
        $fillable = $model->getFillable();

        $errors = importCsv(
            $fileName, 'Team', $fillable, $update);

        if (empty($errors)) {
            \Session::flash('flash_message', "File uploaded successfully.");
        }
        return view('teams.import_csv')->withErrors($errors);
    }
}
