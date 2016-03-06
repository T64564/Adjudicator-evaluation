<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Round;

class RoundController extends Controller {

    public function index() {
        $heads = Round::getTableHeader();
        $values = Round::get();
        return view('rounds.index', compact('heads', 'values'));
    }

    public function create() {
        return view('rounds.create');
    }

    public function store(Request $request) {
        \Debugbar::info($request);
        $this->validateRequest($request);
        return 'A';
        Round::create($request->all());
        $name = $request->name;

        \Session::flash('flash_message', "Create an $name.");
        return redirect()->route('rounds.index');
    }

    public function validateRequest($request) {
        $id = ($request->has('id')) ? ',' . $request->input('id') : '';
        $rules = config('validations.rounds');

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
