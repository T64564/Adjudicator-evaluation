<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Adjudicator;

class AdjudicatorController extends Controller {

    public function index() {
        $heads = Adjudicator::getTableHeader();
        $adjs = Adjudicator::get();
        $rules = config('validations.adjudicators');
        return view('adjudicators.index', compact('heads', 'adjs'));
    }

    public function show() {
        return 'show';
    }

    public function create() {
        return view('adjudicators.create');
    }

    public function store(Request $request) {
        $this->validateRequest($request);
        Adjudicator::create($request->all());
        $name = $request->name;

        \Session::flash('flash_message', "Create $name.");
        return redirect()->route('adjudicators.index');
    }

    public function edit(Adjudicator $adj) {
        return view('adjudicators.edit', compact('adj'));
    }

    public function update(Request $request) {
        $this->validateRequest($request);
        $adj = Adjudicator::findOrFail($request->id);
        
        $adj->update($request->all());
        $name = $request->name;

        \Session::flash('flash_message', "Update $name.");
        return redirect()->route('adjudicators.index');
    }

    public function destroy(Adjudicator $adj) {
        $name = $adj->name;
        $adj->delete();

        \Session::flash('flash_message', "Delete \"$name\".");
        return redirect()->route('adjudicators.index');
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

        $fileName = $file->getClientOriginalName();
        $move = $file->move(storage_path(). '/upload', $fileName);

        $update = false;
        if (\Request('name_dep') === 'update') {
            $update = true;
        }
        $model = new Adjudicator();
        $fillable = $model->getFillable();

        $errors = importCsv(
            $fileName, 'Adjudicator', $fillable, $update);

        if (empty($errors)) {
            \Session::flash('flash_message', "File uploaded successfully.");
        }

        return view('adjudicators.import_csv')->withErrors($errors);
    }

    public function validateRequest($request) {
        $id = ($request->has('id')) ? ',' . $request->input('id') : '';
        $rules = [
            'name' => 'required|unique:adjudicators,name',
            'test_score' => 'required|numeric|between:0,10',
            'active' => 'required|boolean',
        ];

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
