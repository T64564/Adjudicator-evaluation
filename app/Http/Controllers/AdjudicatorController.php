<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Adjudicator;

class AdjudicatorController extends Controller {

    public function index() {
        $heads = Adjudicator::getTableHeader();
        $adjudicators = Adjudicator::get();
        return view('adjudicators.index', compact('heads', 'adjudicators'));
    }

    public function show() {
        return 'show';
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
        return view('adjudicators.import_csv');
    }

    public function postImport() {
        $file = \Input::file('csv');
        if (!isset($file)) {
            \Session::flash('flash_danger', "Specify a file.");
            return redirect()->route('adjudicators.import_csv');
        }

        /* add timestamp */
        $fileName = $file->getClientOriginalName(). '_'. time();
        $move = $file->move(storage_path(). '/upload', $fileName);

        $update = false;
        if (\Request('name_dep') === 'update') {
            $update = true;
        }
        \Debugbar::info(\Request::all());
        $model = new Adjudicator();
        $fillable = $model->getFillable();

        $errors = importCsv(
            $fileName, 'Adjudicator', $fillable, $update);

        if (empty($errors)) {
            \Session::flash('flash_message', "File uploaded successfully.");
        }
        return view('adjudicators.import_csv')->withErrors($errors);
    }
}
