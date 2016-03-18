<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Team;

class TeamController extends Controller {

    public function index() {
        $heads = Team::getTableHeader();
        $teams = Team::get();
        return view('teams.index', compact('heads', 'teams'));
    }

    public function show() {
        return 'show';
    }

    public function create() {
        return view('teams.create');
    }

    public function store(Request $request) {
        $this->validateRequest($request);
        Team::create($request->all());
        $name = $request->name;

        \Session::flash('flash_message', "Create $name.");
        return redirect()->route('teams.index');
    }

    public function edit(Team $team) {
        return view('teams.edit', compact('team'));
    }

    public function update(Request $request) {
        $this->validateRequest($request);
        $team = Team::findOrFail($request->id);
        
        $team->update($request->all());
        $name = $request->name;

        \Session::flash('flash_message', "Update $name.");
        return redirect()->route('teams.index');
    }

    public function destroy(Team $team) {
        $name = $team->name;
        $success = $team->delete();

        if ($success) {
            \Session::flash('flash_message', "Delete \"$name\".");
        } else {
            \Session::flash('flash_danger', "$name is referrenced by feedbacks.");
        }

        return redirect()->route('teams.index');
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
        $model = new Team();
        $fillable = $model->getFillable();

        $errors = importCsv(
            $fileName, 'Team', $fillable, $update);

        if (empty($errors)) {
            \Session::flash('flash_message', "File uploaded successfully.");
        }
        return view('teams.import_csv')->withErrors($errors);
    }

    public function validateRequest($request) {
        $id = ($request->has('id')) ? ',' . $request->input('id') : '';
        $rules = config('validations.teams');

        foreach ($rules as $key => $rule) {
            if (preg_match('/.*boolean.*/', $rule)) {
                $request->merge([$key => $request->has($key)]);
            }
            if (preg_match('/.*unique.*/', $rule)) {
                $rules[$key] .= $id;
            }
        }
        $this->validate($request, $rules);
    }
}
