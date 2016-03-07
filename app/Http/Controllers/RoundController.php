<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Round;

class RoundController extends Controller {

    public function index() {
        $heads = Round::getTableHeader();
        $rounds = Round::get();
        return view('rounds.index', compact('heads', 'rounds'));
    }

    public function create() {
        return view('rounds.create');
    }

    public function store(Request $request) {
        $this->validateRequest($request);
        Round::create($request->all());
        $name = $request->name;

        \Session::flash('flash_message', "Create $name.");
        return redirect()->route('rounds.index');
    }

    public function edit(Round $round) {
        return view('rounds.edit', compact('round'));
    }

    public function update(Request $request) {
        $this->validateRequest($request);
        $round = Round::findOrFail($request->id);
        
        $round->update($request->all());
        $name = $request->name;

        \Session::flash('flash_message', "Update $name.");
        return redirect()->route('rounds.index');
    }

    public function destroy(Round $round) {
        $name = $round->name;
        $round->delete();

        \Session::flash('flash_message', "Delete \"$name\".");
        return redirect('rounds');
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
